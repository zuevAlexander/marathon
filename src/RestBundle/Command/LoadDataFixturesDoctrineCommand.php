<?php

namespace RestBundle\Command;

use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDataFixturesDoctrineCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('nd:symfony:doctrine:fixtures:load');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('doctrine')->getManager()->getConnection()->prepare('SET FOREIGN_KEY_CHECKS = 0;')->execute();
        $result = parent::execute($input, $output);
        $this->getContainer()->get('doctrine')->getManager()->getConnection()->prepare('SET FOREIGN_KEY_CHECKS = 1;')->execute();

        return $result;
    }
}
