<?php
/**
 * @author Vadym Pylypenko<vpylypenko@corevalue.net>
 */

namespace Demo\Translation;


use Symfony\Component\Translation\Formatter\MessageFormatterInterface;
use Symfony\Component\Translation\Translator;

interface TranslatorBuilderInterface
{
    /**
     * Sets default locale.
     *
     * @param string $locale
     * @return $this
     */
    public function setDefaultLocale($locale);

    /**
     * Sets the formatter.
     *
     * @param MessageFormatterInterface $formatter
     * @return $this
     */
    public function setMessageFormatter(MessageFormatterInterface $formatter);

    /**
     * Sets directory for cache.
     *
     * @param string $cacheDir
     * @return $this
     */
    public function setCacheDir($cacheDir);

    /**
     * Sets flag of using cache in debug mode.
     *
     * @param bool $debug
     * @return mixed
     */
    public function setDebug($debug);

    /**
     * Adds loader class.
     *
     * @param string $format
     * @param string $loaderClass
     * @return $this
     */
    public function addLoader($format, $loaderClass);

    /**
     * Adds the loader classes by format.
     *
     * @param array $loaderClasses
     * @return $this
     */
    public function addLoadersByFormat(array $loaderClasses);

    /**
     * Adds the translation resource.
     *
     * @param string $resource
     * @return $this
     */
    public function addResource($resource);

    /**
     * Adds translation resources.
     *
     * @param array $resources
     * @return $this
     */
    public function addResources(array $resources);

    /**
     * Adds the fallback locale.
     *
     * @param string $locale
     * @return $this
     */
    public function addFallbackLocale($locale);

    /**
     * Adds the fallback locales.
     *
     * @param array $locales
     * @return $this
     */
    public function addFallbackLocales(array $locales);

    /**
     * Builds and retrieve the translator.
     *
     * @return Translator
     */
    public function getTranslator();
}