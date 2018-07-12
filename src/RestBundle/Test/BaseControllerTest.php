<?php

namespace RestBundle\Test;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use RestBundle\Exception\Test\Controller\BaseControllerTestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class BaseControllerTest.
 *
 * @method AbstractExecutor loadFixtures(array $classNames, $omName = null, $registryName = 'doctrine', $purgeMode = null)
 */
abstract class BaseControllerTest extends WebTestCase
{
    protected $prefixApi = ''; // can be "/api"

    /**
     * @return string
     */
    protected function getPathToTestCases() : string
    {
        return static::createClient()->getContainer()->get('kernel')->getRootDir().DIRECTORY_SEPARATOR.'..'.
        DIRECTORY_SEPARATOR.'Tests'.
        DIRECTORY_SEPARATOR.'ApiBundle'.DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.'test_cases'.
        DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getPathToLastFailed() : string
    {
        return static::createClient()->getContainer()->get('kernel')->getRootDir().DIRECTORY_SEPARATOR.'..'.
        DIRECTORY_SEPARATOR.'Tests'.
        DIRECTORY_SEPARATOR.'ApiBundle'.DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.'last_failed.log';
    }

    /**
     * @return array
     */
    protected function getPredefinedFixtures() : array
    {
        return [];
    }

    /**
     * @param string $baseUri
     * @param string $caseNameMask
     *
     * @throws \Exception
     */
    protected function assertFromJson(string $baseUri, string $caseNameMask = '')
    {
        $client = static::createClient();
        $action = preg_replace('/\/\{\w+\}/', '', $baseUri);
        $action = str_replace('/', '.', $action);

        $fullPathToTestFile = $this->getPathToTestCases().$action.'.json';
        if (!file_exists($fullPathToTestFile)) {
            throw new BaseControllerTestException('File '.$fullPathToTestFile.' is required for this test');
        }

        $testData = json_decode(
            file_get_contents($fullPathToTestFile),
            true
        );

        if (isset($testData['fixtures'])) {
            $testData['fixtures'] = array_merge($this->getPredefinedFixtures(), $testData['fixtures']);
            $this->getContainer()->get('doctrine')->getManager()->getConnection()->prepare('SET FOREIGN_KEY_CHECKS = 0;')->execute();
            $fixtures = $this->loadFixtures($testData['fixtures'], null, 'doctrine', ORMPurger::PURGE_MODE_TRUNCATE)->getReferenceRepository();
            $this->getContainer()->get('doctrine')->getManager()->getConnection()->prepare('SET FOREIGN_KEY_CHECKS = 1;')->execute();
            unset($testData['fixtures']);
        }

        if (empty($testData)) {
            throw new BaseControllerTestException('There are no test cases in '.$fullPathToTestFile);
        }

        foreach ($testData as $caseName => $data) {
            if (!empty($caseNameMask) && !preg_match('/'.$caseNameMask.'/', $caseName)) {
                continue;
            }

            $request = isset($data['request']) ? $data['request'] : [];

            if (isset($request['reference'])) {
                foreach ($request['reference'] as $reference) {
                    if (!isset($fixtures)) {
                        throw new BaseControllerTestException('There are no fixtures to find reference '.$reference.'. Please, include them into test cases file');
                    }

                    $referenceName = $reference['name'];

                    $referenceObject = $fixtures->getReference($reference['value'])->getId();

                    if (substr($referenceName, -2) == '[]') {
                        $referenceName = substr($referenceName, 0, -2);

                        if (empty($request[$referenceName])) {
                            $request[$referenceName] = [];
                        }

                        $request[$referenceName][] = $referenceObject;
                    } else {
                        $request[$referenceName] = $referenceObject;
                    }
                }
                unset($request['reference']);
            }

            $files = [];
            if (isset($request['files'])) {
                foreach ($request['files'] as $file) {
                    $fileData = $file['data'];

                    $pathToFile = $this->getPathToTestCases().'/files/'.$fileData['name'];

                    $files['files'][$file['name']] = new UploadedFile(
                        $pathToFile,
                        $fileData['name'],
                        $fileData['mimeType'],
                        filesize($pathToFile)
                    );
                }
                unset($request['files']);
            }

            $setSession = isset($data['set_session']) ? $data['set_session'] : [];

            foreach ($setSession as $name => $value) {
                $client->getContainer()->get('session')->set($name, $value);
            }

            $requestUri = $baseUri;

            foreach ($request as $name => $value) {
                if (is_array($value)) {
                    continue;
                }

                $requestUri = str_replace('{'.$name.'}', $value, $requestUri, $count);
                if ($count) {
                    unset($request[$name]);
                }
            }

            $headers = isset($data['headers']) ? $data['headers'] : [];
            $client->request($data['method'], $this->prefixApi.'/'.$requestUri, $request, $files, $headers);

            $actualResponse = json_decode($client->getResponse()->getContent(), true);

            $expectedResponse = $data['response'];
            $errorMessage = 'Failed '.$caseName.PHP_EOL.'Expected response: '.json_encode($expectedResponse).PHP_EOL.'Actual response: '.$client->getResponse()->getContent();
            $this->assertEquals(
                $expectedResponse['status'],
                $client->getResponse()->getStatusCode(),
                $errorMessage
            );

            $this->assertNotEmpty($actualResponse, $errorMessage);
            try {
                $this->assertActualContainsExpected($actualResponse, $expectedResponse['data'], $errorMessage);
            } catch (\Exception $e) {
                file_put_contents(
                    $this->getPathToLastFailed(),
                    $client->getResponse()->getContent()
                );
                throw $e;
            }

            if (isset($data['session'])) {
                foreach ($data['session'] as $name => $expectedValue) {
                    if (is_array($expectedValue)) {
                        $actualValue = (array) $client->getContainer()->get('session')->get($name);
                        $this->assertEmpty(
                            array_diff_assoc($expectedValue, $actualValue),
                            'session is not correct'
                        );
                        $this->assertEmpty(
                            array_diff_assoc($actualValue, $expectedValue),
                            'session is not correct'
                        );
                    } else {
                        $this->assertEquals(
                            $expectedValue,
                            $client->getContainer()->get('session')->get($name)
                        );
                    }
                }
            }
        }
    }

    /**
     * @param array  $actualData
     * @param array  $expectedData
     * @param string $errorMessage
     */
    private function assertActualContainsExpected(array $actualData, array $expectedData, $errorMessage)
    {
        $skipEmptyChecking = false;

        foreach ($expectedData as $key => $expectedChunk) {
            $this->assertArrayHasKey($key, $actualData, $errorMessage);

            if (is_null($expectedChunk)) {
                $skipEmptyChecking = true;
                continue;
            }

            if (is_array(($expectedChunk))) {
                $this->assertActualContainsExpected($actualData[$key], $expectedChunk, $errorMessage);
                $skipEmptyChecking = true;
                continue;
            }

            $this->assertEquals($expectedChunk, $actualData[$key], $errorMessage.PHP_EOL.'`'.$key.'` is not correct');
        }

        if ($skipEmptyChecking) {
            return;
        }

        $unFoundArray = array_diff_assoc($expectedData, $actualData);
        $this->assertEmpty($unFoundArray, $errorMessage."\n".var_export($unFoundArray, true));
    }
}
