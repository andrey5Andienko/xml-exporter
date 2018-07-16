<?php

namespace App\Exporter;

use SimpleXMLElement;

class XmlExporter
{
    /** @var string */
    protected $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function save(string $filename): void
    {
        $content = json_decode($this->content, true);

        $this->checkVersion($content['version']);

        $news = new SimpleXMLElement('<news/>');

        $this->addChildFromArray($content, $news);

        $news->asXML($filename . ".xml");
    }

    protected function addChildFromArray(array $array, SimpleXMLElement &$xml): void
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $child = $xml->addChild($this->replaceIntToString($key));
                $this->addChildFromArray($value, $child);
                continue;
            }

            $xml->addChild($this->replaceIntToString($key), $value);
        }
    }

    protected function replaceIntToString($value): string
    {
        return is_integer($value) ? 'item' : $value;
    }

    protected function checkVersion(string $version): bool
    {
        if ($version === 'https://jsonfeed.org/version/1') {
            return true;
        }
        throw new \Exception('Json version must have https://jsonfeed.org/version/1');
    }
}
