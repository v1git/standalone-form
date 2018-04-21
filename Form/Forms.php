<?php
/**
 * @author Vadym Pylypenko<vpylypenko@corevalue.net>
 */

namespace Demo\Form;


use Demo\Definition\Configuration;
use Demo\Loader\YamlFileLoader;
use Demo\Templating\Helper\FormHelper;
use Demo\Templating\Helper\TranslatorHelper;
use Demo\Templating\TemplateNameParser;
use Demo\Translation\TranslatorBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Form\Extension\Templating\TemplatingRendererEngine;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Translation\TranslatorInterface;

class Forms implements FormsStandaloneInterface
{
    const CONFIG_DIRS = [__DIR__."/../config"];

    private static $instance;

    /** @var  array */
    private $config;

    /** @var  FormFactoryInterface */
    private $formFactory;

    /** @var  EngineInterface */
    private $templating;

    /**
     * Forms constructor.
     */
    private function __construct()
    {
        $this->initializeConfig();
    }

    /**
     * @return Forms
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Prepares and process config.
     */
    private function initializeConfig()
    {
        if (null === $this->config) {
            $delegatingLoader = new DelegatingLoader(new LoaderResolver([new YamlFileLoader(new FileLocator(self::CONFIG_DIRS))]));
            $processor = new Processor();
            $this->config = $processor->processConfiguration(new Configuration(), $delegatingLoader->load('config.yml'));
        }
    }

    /**
     * Retrieve translator configs.
     *
     * @return array
     */
    private function getTranslatorConfig()
    {
        return $this->config['translator'];
    }

    /**
     * Retrieve templating configs.
     *
     * @return array
     */
    private function getTemplatingConfig()
    {
        return $this->config['templating'];
    }

    /**
     * Creates form factory.
     *
     * @return FormFactoryInterface
     */
    private function prepareFormFactory()
    {
        $builder = \Symfony\Component\Form\Forms::createFormFactoryBuilder();
        $validator = \Symfony\Component\Validator\Validation::createValidator();
        $builder->addExtension(new ValidatorExtension($validator));

        return $builder->getFormFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function getFromBuilder()
    {
        if (null === $this->formFactory) {
            $this->formFactory = $this->prepareFormFactory();
        }

        return $this->formFactory->createBuilder();
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplating()
    {
        if (null === $this->templating) {
            $this->templating = $this->prepareTemplating();
        }

        return $this->templating;
    }

    /**
     * Creates templating.
     *
     * @return PhpEngine
     */
    private function prepareTemplating()
    {
        $config = $this->getTemplatingConfig();
        $templating = new PhpEngine(
            new TemplateNameParser(),
            new FilesystemLoader($config['template_path_patterns'])
        );
        $templating->addHelpers([
            new FormHelper(new FormRenderer(new TemplatingRendererEngine($templating, $config['form_themes']))),
            new TranslatorHelper($this->prepareTranslator())
        ]);

        return $templating;
    }

    /**
     * Creates translator.
     *
     * @return TranslatorInterface
     */
    private function prepareTranslator()
    {
        $config = $this->getTranslatorConfig();
        $builder = new TranslatorBuilder();

        $builder->addLoadersByFormat($config['loaders']);
        $builder->setDefaultLocale($config['default_locale']);
        $builder->addResources($config['resources']);

        return $builder->getTranslator();
    }
}