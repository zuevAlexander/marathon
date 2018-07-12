<?php

namespace RestBundle\Generator;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Twig_SimpleFilter;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class DoctrineCrudGenerator
 */
class DoctrineCrudGenerator extends Generator
{
    protected static $output;
    protected static $actionsAvailable = [
        'l' => 'list',
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ];

    protected static $excludedFields = [
        'deletedAt'
    ];

    protected $filesystem;
    protected $rootDir;
    protected $params = [];

    /**
     * @param Filesystem $filesystem
     * @param string $rootDir
     */
    public function __construct(Filesystem $filesystem, $rootDir)
    {
        $this->filesystem = $filesystem;
        $this->rootDir = $rootDir;
    }

    /**
     * @param array $bundles
     * @param string $entity
     * @param ClassMetadataInfo $metadata
     * @param string $actions
     * @param bool $forceOverwrite
     * @return DoctrineCrudGenerator
     */
    public function generateParams(
        array $bundles,
        string $entity,
        ClassMetadataInfo $metadata,
        string $actions,
        bool $forceOverwrite = false
    ): self {
        $entityParts = explode('\\', $entity);
        $entityName = array_pop($entityParts);
        $entityNamespace = implode('\\', $entityParts);
        $entityNamespaceWithSlashes = $entityNamespace ? "\\{$entityNamespace}" : '';
        $entityNamespacePath = str_replace('\\', '/', $entityNamespace);
        $serviceName = strtolower(implode('.', $entityParts)) ?: '';
        $serviceName .= ($serviceName ? '.' : '') . $this->camel2underscored($entityName);

        //params blocks
        $bundleController = [
            'name' => $bundles['bundleController']->getName(),
            'namespace' => $bundles['bundleController']->getNamespace(),
            'path' => $bundles['bundleController']->getPath(),
        ];

        $bundle = [
            'name' => $bundles['bundleEntity']->getName(),
            'namespace' => $bundles['bundleEntity']->getNamespace(),
            'path' => $bundles['bundleEntity']->getPath(),
        ];

        $form = [
            'namespace' => "{$bundle['namespace']}\\Form\\{$entity}",
            'additional' => [
                'selfFormType' => [
                    'is' => false,
                    'name' => '',
                    'toRemoveFields' => [],
                ]
            ],
        ];

        $request = [
            'namespace' => "{$bundle['namespace']}\\Model\\Request\\{$entity}"
        ];


        $fields = $metadata->fieldMappings;
        $associations = $metadata->associationMappings;

        $usesFormAll = $usesRequestAll = $usesServiceDefault = [];

        $usesFormAll[] = 'Symfony\Component\Form\FormBuilderInterface';

        //Exclude some fields
        static::$excludedFields[] = $metadata->identifier[0];
        foreach (static::$excludedFields as $excludedField) {
            if (array_key_exists($excludedField, $fields)) {
                unset($fields[$excludedField]);
            }
        }

        foreach ($fields as $field) {
            if (in_array($field['type'], ['string', 'text'], true)) {
                $usesFormAll[] = 'RestBundle\Form\TextType';
            }
            if ($field['type'] === 'integer') {
                $usesFormAll[] = 'Symfony\Component\Form\Extension\Core\Type\IntegerType';
            }
            if ($field['type'] === 'float') {
                $usesFormAll[] = 'Symfony\Component\Form\Extension\Core\Type\NumberType';
            }
            if ($field['type'] === 'boolean') {
                $usesFormAll[] = 'RestBundle\Form\Type\BooleanType';
            }
            if ($field['type'] === 'datetime') {
                $usesRequestAll[] = $usesServiceDefault[] = 'DateTime';
                $usesFormAll[] = 'Bukashk0zzz\TimestampTypeBundle\Form\Type\TimestampType';
            }
        }

        if (count($associations)) {
            $usesFormAll[] = 'Symfony\Bridge\Doctrine\Form\Type\EntityType';
            foreach ($associations as &$association) {
                $targetEntityExploded = explode('\\', $association['targetEntity']);
                $association['targetEntityName'] = array_pop($targetEntityExploded);

                if ($association['mappedBy'] !== null) {
                    $usesFormAll[] = 'Symfony\Component\Form\Extension\Core\Type\CollectionType';
                    $usesFormTypeNamespace = "{$bundle['namespace']}\\Form\\" . $association['targetEntityName'];

                    $associationRequestNamespace = "{$bundle['namespace']}\\Model\\Request\\" . $association['targetEntityName'];

                    if ($usesFormTypeNamespace !== $form['namespace']) {
                        $usesFormAll[] = $usesFormTypeNamespace . '\\' . $association['targetEntityName'] . 'CreateType';

                        $usesRequestAll[] = $associationRequestNamespace . '\\' . $association['targetEntityName'] . 'CreateRequest';
                    } else {
                        // CollectionType try to make recursive, for this we will create a special form
                        $association['selfFormType']['name'] = $association['targetEntityName'] . 'CreateSelfType';
                        $form['additional']['selfFormType']['is'] = true;
                        $form['additional']['selfFormType']['name'] = $association['selfFormType']['name'];
                        $form['additional']['selfFormType']['toRemoveFields'][] = $association['fieldName'];
                        $form['additional']['selfFormType']['toRemoveFields'][] = $association['mappedBy'];
                    }
                    $usesServiceDefault[] = $associationRequestNamespace . '\\' . $association['targetEntityName'] . 'CreateRequest';
                } else {
                    $usesRequestAll[] = $usesServiceDefault[] = $association['targetEntity'];
                    $usesFormAll[] = $association['targetEntity'];
                }
            }
            unset($association);
        }

        $this->params = [
            'overwrite' => $forceOverwrite,
            'actions' => array_intersect_key(static::$actionsAvailable, array_flip(str_split($actions))),
            'form' => $form,
            'request' => $request,
            'handler' => [
                'namespace' => "{$bundle['namespace']}\\Handler{$entityNamespaceWithSlashes}",
                'class' => "{$bundle['namespace']}\\Handler\\{$entity}Handler",
                'service_name' => $serviceName
            ],
            'service' => [
                'namespace' => "{$bundle['namespace']}\\Service\\{$entity}",
                'class' => "{$bundle['namespace']}\\Service\\{$entity}\\{$entityName}Service",
                'service_name' => $serviceName
            ],
            'entity' => [
                'class' => $bundle['namespace'] . '\\Entity\\' . $entity,
                'name_with_namespace' => $entity,
                'name' => $entityName,
                'name_lcfirst' => lcfirst($entityName),
                'name_ucfirst' => ucfirst($entityName),
                'name_underscored' => $this->camel2underscored($entityName),
                'namespace' => $entityNamespace,
                'namespace_path' => $entityNamespacePath,
                'singularized' => lcfirst(Inflector::singularize($entityName)),
                'entity_pluralized' => lcfirst(Inflector::pluralize($entityName)),
                'identifier' => $metadata->identifier[0],
                'fields' => $fields,
                'associations' => $associations,
            ],
            'bundle' => $bundle,
            'controller' => [
                'namespace' => "{$bundleController['namespace']}\\Controller{$entityNamespaceWithSlashes}",
                'class' => "{$bundleController['namespace']}\\Controller\\{$entity}Controller",
                'bundle' => $bundleController
            ],
            'uses' => [
                'form' => [
                    'all' => array_unique($usesFormAll)
                ],
                'request' => [
                    'all' => array_unique($usesRequestAll)
                ],
                'service' => [
                    'default' => array_unique($usesServiceDefault)
                ],
            ]
        ];

        return $this;
    }

    /**
     * Generate LCRUD
     */
    public function generate()
    {
        $this->generateRequests();
        $this->generateHandler();
        $this->generateForms();
        $this->generateControllerClass();
        $this->generateRouting();
        $this->generateService();
    }

    /**
     * Generate Requests
     */
    protected function generateRequests()
    {
        $params = &$this->params;
        /**
         * @var array $actions
         */
        $actions = &$params['actions'];
        $entity = &$params['entity'];
        $pathTemplate = sprintf(
            '%s/Model/Request/%s/%s/%s%s.php',
            $params['bundle']['path'],
            $entity['namespace_path'],
            $entity['name'],
            $entity['name'],
            '%s'
        );

        if (count(array_intersect(['read', 'update', 'delete'], $actions)) > 0) {
            $this->generateFile(
                'crud/model/request/interfaces/single.php.twig',
                sprintf($pathTemplate, 'SingleRequestInterface')
            );
            $this->generateFile(
                'crud/model/request/traits/single.php.twig',
                sprintf($pathTemplate, 'SingleRequestTrait')
            );
        }
        if (count(array_intersect(['create', 'update'], $actions)) > 0) {
            $this->generateFile(
                'crud/model/request/interfaces/all.php.twig',
                sprintf($pathTemplate, 'AllRequestInterface')
            );
            $this->generateFile('crud/model/request/traits/all.php.twig', sprintf($pathTemplate, 'AllRequestTrait'));
        }

        foreach ($actions as $action) {
            $path = sprintf($pathTemplate, ucfirst($action) . 'Request');
            $this->generateFile("crud/model/request/{$action}.php.twig", $path);
        }
    }

    /**
     * Generates the controller class only.
     */
    protected function generateControllerClass()
    {
        $path = sprintf(
            '%s/Controller/%s/%sController.php',
            $this->params['controller']['bundle']['path'],
            $this->params['entity']['namespace_path'],
            $this->params['entity']['name']
        );

        $this->generateFile('crud/controllers/controller.php.twig', $path);
    }

    public function generateRouting()
    {
        $params = &$this->params;
        $path = sprintf('%s/Resources/config/routing.yml', $params['controller']['bundle']['path']);

        try {
            $routing = Yaml::parse(file_get_contents($path));
        } catch (ParseException $e) {
            printf('Unable to parse the YAML string: %s', $e->getMessage());
        }

        $bundleName = &$params['controller']['bundle']['name'];
        $routingNamePrefix = strtolower(substr($bundleName, 0, strpos($bundleName, 'Bundle')));
        $routingName = $this->camel2underscored($routingNamePrefix . $params['entity']['name']);

        $routing[$routingName] = [
            'resource' => sprintf('@%s.php', str_replace('\\', '/', $params['controller']['class'])),
            'type' => 'rest',
            'prefix' => '/',
            'defaults' => [
                '_format' => 'json',
            ]
        ];

        $routingYaml = Yaml::dump($routing, 4, 2);

        file_put_contents($path, $routingYaml);
    }

    /**
     * Generate Forms
     */
    protected function generateHandler()
    {
        $params = &$this->params;
        $entity = &$params['entity'];

        $pathInterface = sprintf(
            '%s/Model/Handler/%s/%sProcessorInterface.php',
            $params['bundle']['path'],
            $entity['namespace_path'],
            $entity['name']
        );
        $pathClass = sprintf(
            '%s/Handler/%s/%sHandler.php',
            $params['bundle']['path'],
            $entity['namespace_path'],
            $entity['name']
        );

        $this->generateFile('crud/model/handler/processor-interface.php.twig', $pathInterface);
        $this->generateFile('crud/handler/handler.php.twig', $pathClass);

        $this->generateConfigServices();
    }

    /**
     * Generate Forms
     */
    protected function generateForms()
    {
        $params = &$this->params;
        /**
         * @var array $actions
         */
        $actions = &$params['actions'];
        $entity = &$params['entity'];
        $pathTemplate = sprintf(
            '%s/Form/%s/%s/%s%s.php',
            $params['bundle']['path'],
            $entity['namespace_path'],
            $entity['name'],
            $entity['name'],
            '%s'
        );

        foreach ($actions as $action) {
            $path = sprintf($pathTemplate, ucfirst($action) . 'Type');
            $this->generateFile("crud/forms/{$action}.php.twig", $path);
        }

        if (count(array_intersect(['read', 'update', 'delete'], $actions)) > 0) {
            $this->generateFile('crud/forms/traits/single.php.twig', sprintf($pathTemplate, 'SingleTypeTrait'));
        }
        if (count(array_intersect(['create', 'update'], $actions)) > 0) {
            $this->generateFile('crud/forms/traits/all.php.twig', sprintf($pathTemplate, 'AllTypeTrait'));

            if ($params['form']['additional']['selfFormType']['is']) {
                $this->generateFile('crud/forms/create-self.php.twig', sprintf($pathTemplate, 'CreateSelfType'));
            }

        }
    }

    public function generateFile(string $template, string $path)
    {
        $path = str_replace('//', '/', $path);
        if (file_exists($path)) {
            if ($this->params['overwrite']) {
                $contentNew = $this->render($template, $this->params);
                if ($contentNew !== file_get_contents($path)) {
                    $pathInfo = pathinfo($path);
                    $path = sprintf('%s/%s~.%s', $pathInfo['dirname'], $pathInfo['filename'], $pathInfo['extension']);
                    self::dump($path, $contentNew);
                }
                return;
            } else {
                $message = sprintf("Unable to generate the file as it already exists.\n\t<fg=yellow>%s</>",  $path);
                static::writeln($message);
                return;
            }
        }

        $this->renderFile($template, $path, $this->params);
    }

    public function camel2underscored(string $string): string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $string));
    }

    public function generateConfigServices()
    {
        $params = &$this->params;
        $resourceName = $params['entity']['name'] . 'Services.yml';
        $path = sprintf('%s/Resources/config/%s', $params['bundle']['path'], $resourceName);
        $services = $this->getConfig($path);

        $handlerServiceName = "core.handler.{$params['handler']['service_name']}";
        $handlerParameterName = "{$handlerServiceName}.class";

        $services['parameters'][$handlerParameterName] = $params['handler']['class'];
        $services['services'][$handlerServiceName] = [
            'class' => "%{$handlerParameterName}%",
            'arguments' => [
                '@service_container',
                '@event_dispatcher',
                "@core.service.{$params['service']['service_name']}",
            ]
        ];

        $serviceServiceName = "core.service.{$params['handler']['service_name']}";
        $entityParameterName = "core.entity.{$params['handler']['service_name']}.class";
        $serviceParameterName = "{$serviceServiceName}.class";

        $services['parameters'][$entityParameterName] = $params['entity']['class'];
        $services['parameters'][$serviceParameterName] = $params['service']['class'];
        $services['services'][$serviceServiceName] = [
            'class' => "%{$serviceParameterName}%",
            'tags' => [
                [
                    'name' => 'kernel.event_subscriber'
                ]
            ],
            'arguments' => [
                '@service_container',
                "%{$entityParameterName}%",
                '@event_dispatcher',
            ]
        ];
        $this->saveConfig($services, $path);

        // Include the entity-service config to main service-config file
        $path = "{$params['bundle']['path']}/Resources/config/services.yml";
        $services = $this->getConfig($path, false);
        $resource = ['resource' => $resourceName];

        if (!array_key_exists('imports', $services)) {
            $services = array_merge(['imports' => []], $services);
        }
        if (!in_array($resource, $services['imports'], true)) {
            $services['imports'][] = $resource;
        }
        $this->saveConfig($services, $path);
    }

    /**
     * @param string $path
     * @return array
     */
    public function getConfig(string $path, bool $checkOverwrite = true): array
    {
        $services = [];

        if (file_exists($path)) {
            if ($checkOverwrite && !$this->params['overwrite']) {
                $message = sprintf("Unable to generate the file as it already exists.<fg=yellow>\n\t%s</>",  $path);
                static::writeln($message);
                return [];
            }
            try {
                $parseResult = Yaml::parse(file_get_contents($path));
                if (is_array($parseResult)) {
                    $services = $parseResult;
                }
            } catch (ParseException $e) {
                printf('Unable to parse the YAML string: %s', $e->getMessage());
            }
        }

        return $services;
    }

    /**
     * @param array $configs
     * @param string $path
     * @return int
     */
    public function saveConfig(array $configs, string $path): int
    {
        $servicesYaml = Yaml::dump($configs, 4, 2);
        self::mkdir(dirname($path));
        return file_put_contents($path, $servicesYaml);
    }

    /**
     * Generate Service fore entity
     */
    public function generateService()
    {
        $params = &$this->params;
        $entity = &$params['entity'];
        $template = sprintf(
            '%s/Service/%s/%s/%s%s.php',
            $params['bundle']['path'],
            $entity['namespace_path'],
            $entity['name'],
            $entity['name'],
            '%s'
        );

        $this->generateFile('crud/service/interfaces/default-values.php.twig',
            sprintf($template, 'DefaultValuesInterface'));
        $this->generateFile('crud/service/traits/default-values.php.twig', sprintf($template, 'DefaultValuesTrait'));
        $this->generateFile('crud/service/service.php.twig', sprintf($template, 'Service'));
    }

    /**
     * @param $prefix
     * @return mixed|string
     */
    public static function getRouteNamePrefix($prefix)
    {
        $prefix = preg_replace('/{(.*?)}/', '', $prefix); // {foo}_bar -> _bar
        $prefix = str_replace('/', '_', $prefix);
        $prefix = preg_replace('/_+/', '_', $prefix);     // foo__bar -> foo_bar
        $prefix = trim($prefix, '_');

        return $prefix;
    }


    /**
     * @inheritdoc
     */
    protected function getTwigEnvironment()
    {
        $twig = parent::getTwigEnvironment();
        $twig->addFilter(new Twig_SimpleFilter('ucfirst', 'ucfirst'));
        $twig->addFilter(new Twig_SimpleFilter('lcfirst', 'lcfirst'));
        return $twig;
    }

    protected static function writeln($message)
    {
        if (null === self::$output) {
            self::$output = new ConsoleOutput();
        }

        self::$output->writeln($message);
    }
}
