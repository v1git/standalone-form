<?php
/**
 * @author Vadym Pylypenko<vpylypenko@corevalue.net>
 */

namespace Demo\Loader;


use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Parser;

class YamlFileLoader extends FileLoader
{
    private $yamlParser;

    public function load($resource, $type = null)
    {
        $parser = $this->getYamlParser();
        return $parser->parse(file_get_contents($this->locator->locate($resource)));
    }

    public function supports($resource, $type = null)
    {
        if (!is_string($resource)) {
            return false;
        }

        if (null === $type) {
            $type = pathinfo($resource, PATHINFO_EXTENSION);
        }

        return in_array($type, ['yaml', 'yml'], true);
    }

    private function getYamlParser()
    {
        if (null === $this->yamlParser) {
            $this->yamlParser = new Parser();
        }

        return $this->yamlParser;
    }
}