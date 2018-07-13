<?php

namespace App\Exporter;

use SimpleXMLElement;

class XmlExporter
{
    public function jsonToXml($content, string $filename)
    {
        $content = json_decode($content);

        $news = new SimpleXMLElement('<news/>');

        static::checkVersion($content->version);

        $this->addChildFromArray($content, $news);

        $news->asXML($filename . ".xml");
    }

    public function addChildFromArray($array, SimpleXMLElement &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $child = $xml->addChild($this->replaceIntToString($key));
                $this->addChildFromArray($value, $child);
            } else {
                $xml->addChild($this->replaceIntToString($key), $value);
            }
        }
    }

    public function replaceIntToString($value)
    {
        if (is_integer($value)) {
            return 'item';
        }

        return $value;
    }

    protected function checkVersion(string $version)
    {
        if ($version === 'https://jsonfeed.org/version/1') {
            return true;
        }
        throw new \Exception('Json version must have https://jsonfeed.org/version/1');
    }
}