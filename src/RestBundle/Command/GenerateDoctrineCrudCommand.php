<?php

namespace RestBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand as Base;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use RestBundle\Generator\DoctrineCrudGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class GenerateDoctrineCrudCommand
 */
class GenerateDoctrineCrudCommand extends Base
{
    protected function configure()
    {
        $this->setName('nd:symfony:command:crud:generate')
            ->setAliases(array('nd:symfony:command:generate:crud'))
            ->setDescription('Generate CRUD')
            ->setDefinition([
//                new InputArgument('entity', InputArgument::OPTIONAL,
//                    'The entity class name to initialize (shortcut notation)'),
                new InputOption('entity', '', InputOption::VALUE_REQUIRED,
                    'The entity class name to initialize (shortcut notation)'),
                new InputOption('bundle-controller', 'c', InputOption::VALUE_REQUIRED,
                    'The name of the bundle for controllers', 'ApiBundle'),
                new InputOption('actions', 'a', InputOption::VALUE_OPTIONAL,
                    'What actions do you want to generate (List, Create, Read, Update, Delete?)', 'lcrud'),
                new InputOption('overwrite', 'o', InputOption::VALUE_NONE,
                    'Overwrite any existing class when generating the CRUD'),
            ])
            ->setHelp('This command allows you to generate basic code for CRUD');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $input->setInteractive(true);

//        if ($input->isInteractive()) {
//            $question = new ConfirmationQuestion(
//                $questionHelper->getQuestion('Do you confirm generation', 'yes', '?'),
//                true
//            );
//            if (!$questionHelper->ask($input, $output, $question)) {
//                $output->writeln('<error>Command aborted</error>');
//
//                return 1;
//            }
//        }

        $entityShortcutNotation = Validators::validateEntityName($input->getOption('entity'));
        list($bundleEntity, $entity) = $this->parseShortcutNotation($entityShortcutNotation);

        $questionHelper->writeSection($output, 'Begin CRUD generation');

        try {
            $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundleEntity) . '\\' . $entity;
            $metadata = $this->getEntityMetadata($entityClass);
        } catch (\Exception $e) {
            throw new \RuntimeException(
                sprintf('Entity "%s" does not exist in the "%s" bundle. Create it with the "doctrine:generate:entity"
                    command and then execute this command again.', $entity, $bundleEntity)
            );
        }

        if (count($metadata[0]->identifier) !== 1) {
            throw new \RuntimeException(
                'The CRUD generator does not support entity classes with multiple or no primary keys.'
            );
        }

        $bundles = [
            'bundleEntity' => $this->getKernel()->getBundle($bundleEntity),
            'bundleController' => $this->getKernel()->getBundle($input->getOption('bundle-controller'))
        ];

        /**
         * @var DoctrineCrudGenerator $generator
         */
        $generator = $this->getGenerator($bundles['bundleEntity']);
        $generator->generateParams(
            $bundles,
            $entity,
            $metadata[0],
            $input->getOption('actions'),
            $input->getOption('overwrite')
        )
            ->generate(
            $bundles,
            $entity,
            $metadata[0],
            $input->getOption('actions'),
            $input->getOption('overwrite')
        );

        $questionHelper->writeSection($output, 'Finnish CRUD generation');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the CRUD generator');
    }

    /**
     * @param null|BundleInterface $bundle
     * @return DoctrineCrudGenerator
     */
    protected function createGenerator($bundle = null)
    {
        return new DoctrineCrudGenerator(
            $this->getContainer()->get('filesystem'),
            $this->getContainer()->getParameter('kernel.root_dir')
        );
    }


    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();

        $dir = $bundle->getPath() . '/Resources/skeleton';
        if (null !== $bundle && is_dir($dir)) {
            $skeletonDirs[] = $dir;
        }

        $dir = $this->getKernel()->getRootDir() . '/Resources/skeleton';
        if (is_dir($dir)) {
            $skeletonDirs[] = $dir;
        }

        $skeletonDirs[] = __DIR__ . '/../Resources/skeleton';
        $skeletonDirs[] = __DIR__ . '/../Resources';

        return $skeletonDirs;
    }

    /**
     * @return KernelInterface
     */
    protected function getKernel()
    {
        return $this->getContainer()->get('kernel');
    }
}
