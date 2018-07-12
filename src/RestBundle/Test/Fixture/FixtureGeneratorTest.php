<?php

namespace RestBundle\Test\Fixture;

use RestBundle\Fixture\FixtureGenerator;
use RestBundle\Fixture\FixtureGenerator\FixtureGeneratorRule;
use RestBundle\Fixture\FixtureGenerator\FixtureGeneratorStrategy;
use PHPUnit\Framework\TestCase;

class FixtureGeneratorTest extends TestCase
{
    /**
     * @var FixtureGenerator
     */
    private $generator;

    protected function setUp()
    {
        parent::setUp();
        $this->generator = new FixtureGenerator();
    }

    public function testGenerate()
    {
        $rule = new FixtureGeneratorRule();

        $rule->setKey('test')
             ->setPrefix('user')
             ->setDelimiter(' ')
             ->setStrategy(FixtureGeneratorStrategy::PREFIX_WITH_NUMBER)
             ->setCount(5);

        $json = $this->generator->generate($rule);
    }

    public function testGenerateTwoDimensional()
    {
        $rule = $this->createTestRule();

        $json = $this->generator->generate($rule);
    }

    public function testGenerateWithReferences()
    {
        $rule = $this->createTestRule();

        $referenceArray = [
            [
                'referenceName' => 'user-1',
            ],
            [
                'referenceName' => 'user-2',
            ],
        ];

        $membersRule = new FixtureGeneratorRule();
        $membersRule->setKey('members')->setCountRepeat(5);

        $memberRule = new FixtureGeneratorRule();
        $memberRule->setStrategy(FixtureGeneratorStrategy::REFERENCE)
                   ->setReferenceArray($referenceArray);

        $membersRule->addRule($memberRule);

        $rule->addRule($membersRule);

        $ownerRule = new FixtureGeneratorRule();
        $ownerRule->setKey('owner')
                  ->setStrategy(FixtureGeneratorStrategy::REFERENCE)
                  ->setReferenceArray($referenceArray);

        $rule->addRule($ownerRule);

        $json = $this->generator->generate($rule);
    }

    public function testGenerateFromJsonConfig()
    {
        $this->generator->saveFromConfig(__DIR__.'/config.json', __DIR__.'/UserFixtures_output.json');
    }

    public function testSave()
    {
        $rule = $this->createTestRule();

        $fileName = __DIR__.'/UserFixtures.json';

        $this->generator->save($fileName, $rule);
    }

    /**
     * @return FixtureGeneratorRule
     */
    private function createTestRule()
    {
        $rule = new FixtureGeneratorRule();

        $rule->setKey('user')->setCountRepeat(5);

        foreach ([
                     [
                         'key' => 'referenceName',
                         'prefix' => 'user',
                         'strategy' => FixtureGeneratorStrategy::PREFIX_WITH_NUMBER,
                     ],
                     [
                         'key' => 'name',
                         'prefix' => 'User',
                         'strategy' => FixtureGeneratorStrategy::PREFIX_WITH_NUMBER,
                     ],
                     [
                         'key' => 'token',
                         'prefix' => 'user-token',
                         'strategy' => FixtureGeneratorStrategy::PREFIX_WITH_NUMBER,
                     ],
                     [
                         'key' => 'password',
                         'prefix' => 'User',
                         'strategy' => FixtureGeneratorStrategy::PREFIX_WITH_NUMBER,
                     ],
                 ] as $item) {
            $subRule = new FixtureGeneratorRule();

            $subRule->setKey($item['key'])
                ->setPrefix($item['prefix'])
                ->setStrategy($item['strategy']);

            $rule->addRule($subRule);
        }

        return $rule;
    }
}
