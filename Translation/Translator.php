<?php
/**
 * @author Vadym Pylypenko<vpylypenko@corevalue.net>
 */

namespace Demo\Translation;

use Symfony\Component\Translation\Translator as BaseTranslator;

class Translator extends BaseTranslator
{
    protected $lazyLoaders;

    public function __construct($locale, $formatter = null, $cacheDir = null, $debug = false, array $lazyLoaders = [])
    {
        $this->lazyLoaders = $lazyLoaders;
        parent::__construct($locale, $formatter, $cacheDir, $debug);
    }

    /**
     * {@inheritdoc}
     */
    public function addResource($format, $resource, $locale, $domain = null)
    {
        if (!in_array($format, $this->getLoaderFormats(), true) && ($loader = $this->createLoader($format))) {
            unset($this->lazyLoaders[$format]);
            $this->addLoader($format, $loader);
        }

        parent::addResource($format, $resource, $locale, $domain);
    }

    /**
     * Retrieve available loader formats.
     *
     * @return array
     */
    protected function getLoaderFormats()
    {
        return array_keys($this->getLoaders());
    }

    /**
     * Initialize loaders
     *
     * @param $format
     */
    protected function createLoader($format)
    {
        if (isset($this->lazyLoaders[$format])) {
            return $this->lazyLoaders[$format]();
        }

        return;
    }
}