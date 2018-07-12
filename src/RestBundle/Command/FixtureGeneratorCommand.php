<?php

namespace RestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixtureGeneratorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('nd:symfony:command:fixtures:generate')
            ->addArgument('pathToFixtures')
            ->setDescription('Generate fixtures');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('nd.symfony.fixtures.generator')
             ->setPathToFixtures($input->getArgument('pathToFixtures'))
             ->generateAllFixtures();

        $output->writeln('Fixtures have been generated');
    }
}
