<?php

namespace RestBundle\Fixture;

use Doctrine\Common\Util\Inflector;
use JMS\Serializer\SerializerBuilder;
use RestBundle\Exception\Fixture\FixtureGeneratorException;
use RestBundle\Fixture\FixtureGenerator\FixtureGeneratorRule;
use RestBundle\Fixture\FixtureGenerator\FixtureGeneratorStrategy;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class FixtureGenerator
{
    /**
     * @var string
     */
    private $pathToFixtures;

    /**
     * @param string $pathToFixtures
     *
     * @return FixtureGenerator
     */
    public function setPathToFixtures(string $pathToFixtures): self
    {
        $this->pathToFixtures = $pathToFixtures;

        return $this;
    }

    /**
     * @return string
     *
     * @throws FixtureGeneratorException
     */
    public function getPathToFixtures(): string
    {
        if (!isset($this->pathToFixtures)) {
            throw new FixtureGeneratorException('You must define pathToFixtures for generator');
        }

        return $this->pathToFixtures;
    }

    /**
     * @param FixtureGeneratorRule $rule
     *
     * @return string json string
     *
     * @throws FixtureGeneratorException
     */
    public function generate(FixtureGeneratorRule $rule): string
    {
        return json_encode($this->generateArrayForRule($rule), JSON_PRETTY_PRINT);
    }

    /**
     * @param FixtureGeneratorRule $rule
     *
     * @return array|string
     */
    private function generateArrayForRule(FixtureGeneratorRule $rule)
    {
        if (!empty($rule->getRules())) {
            return $this->generateSubRules($rule);
        }

        $chunks = [];

        for ($i = 0; $i < $rule->getCount(); ++$i) {
            if (!empty($rule->getPrefix())) {
                $chunks[] = $rule->getPrefix().'-'.$this->generateChunk($rule);
            } else {
                $chunks[] = $this->generateChunk($rule);
            }
        }

        return implode($rule->getDelimiter(), $chunks);
    }

    /**
     * @param FixtureGeneratorRule $rule
     *
     * @return int|string
     *
     * @throws \Exception
     */
    private function generateChunk(FixtureGeneratorRule $rule)
    {
        switch ($rule->getStrategy()) {
            case FixtureGeneratorStrategy::PREFIX_WITH_NUMBER:
                $value = $rule->getNumber();

                $rule->setNumber($rule->getNumber() + 1);

                return $value;
            case FixtureGeneratorStrategy::REFERENCE:
                if (!empty($rule->getReferenceArray())) {
                    $referenceArray = $rule->getReferenceArray();
                }

                if (!empty($rule->getReferenceConfigFile())) {
                    $referenceArray = json_decode(
                        file_get_contents(
                            $this->getPathToFixtures().DIRECTORY_SEPARATOR.$rule->getReferenceConfigFile()
                        ),
                        true
                    );
                }

                if (empty($referenceArray)) {
                    throw new \Exception('ReferenceArray is needed for the rule: ');
                }

                return $referenceArray[mt_rand(0, count($referenceArray) - 1)]['referenceName'];
            case FixtureGeneratorStrategy::WORD:
                if (empty($rule->getWords())) {
                    throw new \Exception('words is needed for the rule `words`');
                }

                return $rule->getWords()[mt_rand(0, count($rule->getWords()) - 1)];
            case FixtureGeneratorStrategy::NEXT_WORD:
                if (empty($rule->getWords())) {
                    throw new \Exception('words is needed for the rule `next_word`');
                }

                $word = $rule->getWords()[$rule->getNumber()];

                if (isset($rule->getWords()[$rule->getNumber() + 1])) {
                    $rule->setNumber($rule->getNumber() + 1);
                } else {
                    $rule->setNumber(0);
                }

                return $word;
            default:
                return mt_rand(1, 1000);
        }
    }

    /**
     * @param FixtureGeneratorRule $rule
     *
     * @return array
     */
    private function generateSubRules(FixtureGeneratorRule $rule)
    {
        $subRuleArray = [];

        for ($i = 0; $i < $rule->getCountRepeat(); ++$i) {
            foreach ($rule->getRules() as $subRule) {
                empty($subRule->getKey()) ? $subRuleArray[$i] = $this->generateArrayForRule($subRule) :
                                            $subRuleArray[$i][$subRule->getKey()] = $this->generateArrayForRule($subRule);
            }
        }

        return $subRuleArray;
    }

    /**
     * @param string               $fileName
     * @param FixtureGeneratorRule $rule
     */
    public function save(string $fileName, FixtureGeneratorRule $rule)
    {
        file_put_contents($fileName, $this->generate($rule));
    }

    /**
     * @param string $configFileName
     * @param string $destinationPath
     *
     * @internal param string $configDirectoryPath
     */
    public function saveFromConfig(string $configFileName, string $destinationPath)
    {
        if (!file_exists($configFileName)) {
            throw new FileNotFoundException('File not found: '.$configFileName);
        }

        $configJson = file_get_contents($configFileName);

        $rule = SerializerBuilder::create()->build()->deserialize($configJson, FixtureGeneratorRule::class, 'json');

        file_put_contents($destinationPath, json_encode($this->generateArrayForRule($rule), JSON_PRETTY_PRINT));
    }

    public function generateAllFixtures()
    {
        $pathToConfig = $this->getPathToFixtures().DIRECTORY_SEPARATOR.'config';

        $files = glob($pathToConfig.DIRECTORY_SEPARATOR.'*');

        if (empty($files)) {
            throw new FileNotFoundException('There are no config files in directory '.$pathToConfig);
        }

        foreach ($files as $configFileName) {
            $camelizeName = preg_replace('/\d/', '', ucfirst(
                Inflector::camelize(basename($configFileName))
            ));
            $destination = str_replace(
                '.json',
                'Fixtures.json',
                $camelizeName
            );
            $this->saveFromConfig(
                $configFileName,
                $this->getPathToFixtures().DIRECTORY_SEPARATOR.$destination
            );
        }
    }

    /**
     * @param array  $config
     * @param string $configPath
     *
     * @return FixtureGeneratorRule
     *
     * @throws \Exception
     */
    private function createRuleFromConfig(array $config, string $configPath): FixtureGeneratorRule
    {
        if (empty($config)) {
            throw new \Exception('Config array is empty');
        }

        $rule = new FixtureGeneratorRule();

        if (!empty($key)) {
            $rule->setKey($key);
        }

        if (isset($config['prefix'])) {
            $rule->setPrefix($config['prefix']);
        }

        if (isset($config['strategy'])) {
            $rule->setStrategy($config['strategy']);
        }

        if (isset($config['count_repeat'])) {
            $rule->setCountRepeat($config['count_repeat']);
        }

        if (isset($config['referenceArray'])) {
            $rule->setReferenceArray(
                json_decode(
                    file_get_contents($configPath.'/'.$config['referenceArray'].'.json'),
                    true
                )
            );
        }

        if (isset($config['rules'])) {
            foreach ($config['rules'] as $key => $item) {
                $rule->addRule($this->createRuleFromConfig($item, $configPath));
            }
        }

        /* @var FixtureGeneratorRule $rule */
        return $rule;
    }

    /**
     * @param string $key
     * @param array  $ruleArray
     *
     * @return FixtureGeneratorRule
     */
    private function createRuleFromArray(string $key, array $ruleArray): FixtureGeneratorRule
    {
        $rule = new FixtureGeneratorRule();

        if (!empty($key)) {
            $rule->setKey($key);
        }

        if (isset($ruleArray['prefix'])) {
            $rule->setPrefix($ruleArray['prefix']);
        }

        if (isset($ruleArray['strategy'])) {
            $rule->setStrategy($ruleArray['strategy']);
        }

        if (isset($ruleArray['count_repeat'])) {
            $rule->setCountRepeat($ruleArray['count_repeat']);
        }

        return $rule;
    }
}
