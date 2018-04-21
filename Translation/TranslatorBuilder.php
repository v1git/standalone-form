<?php
/**
 * @author Vadym Pylypenko<vpylypenko@corevalue.net>
 */

namespace Demo\Translation;


use Symfony\Component\Translation\Formatter\MessageFormatterInterface;

class TranslatorBuilder implements TranslatorBuilderInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var MessageFormatterInterface
     */
    private $formatter;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var array
     */
    private $loaders = [];

    /**
     * @var array
     */
    private $resources = [];

    /**
     * @var array
     */
    private $fallbackLocale;

    /**
     * {@inheritdoc}
     */
    public function setDefaultLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessageFormatter(MessageFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addLoader($format, $loaderClass)
    {
        $this->loaders[$format] = function () use ($loaderClass) {
            return new $loaderClass;
        };
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addResource($resource)
    {
        $this->resources[] = $resource;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addResources(array $resources)
    {
        $this->resources = array_merge($this->resources, $resources);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addLoadersByFormat(array $loaderClasses)
    {
        foreach ($loaderClasses as $format => $loaderClasses) {
            $this->addLoader($format, $loaderClasses);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFallbackLocale($locale)
    {
        $this->fallbackLocale[] = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function addFallbackLocales(array $locales)
    {
        $this->fallbackLocale = array_merge($this->fallbackLocale, $locales);
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslator()
    {
        if (null === $this->locale) {
            throw new \LogicException('You must specify default locale for the translator');
        }

        $translator = new Translator($this->locale, $this->formatter, $this->cacheDir, $this->debug, $this->loaders);

        foreach ($this->resources as $resource) {
            list($domain, $locale, $format) = explode('.', basename($resource), 3);
            $translator->addResource($format, $resource, $locale, $domain);
        }

        return $translator;
    }
}