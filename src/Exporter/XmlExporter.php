<?php

namespace App\Exporter;

use SimpleXMLElement;

class XmlExporter
{
    public static function jsonToXml($content, string $filename)
    {
        $a = new SimpleXMLElement('<news/>');

        static::checkVersion($content->version);

        foreach ($content as $key => $i) {
            if (is_array($i)) {
                $a->addChild($key);
                continue;
            }
            $a->addChild($key, $i);
        }

        foreach ($content->items as $numberKey => $contentItem) {

            $childrenName = "item";

            $a->items->addChild($childrenName);

            foreach ($contentItem as $key => $item) {
                if (is_array($item)) {

                    foreach ($item as $itemKey => $itemValue) {
                        $a->items->$childrenName[$numberKey]->$key->$itemKey = $itemValue;
                    }

                    continue;
                }
                if (is_object($item)) {
                    foreach ($item as $k => $v) {
                        $a->items->$childrenName[$numberKey]->$key->$k = $v;
                    }

                    continue;
                }

                $a->items->$childrenName[$numberKey]->$key = $item;
            }
        }
        $a->asXML($filename . ".xml");
    }

    protected function checkVersion(string $version)
    {
        if ($version === 'https://jsonfeed.org/version/1') {
            return true;
        }
        throw new \Exception('Json version must have https://jsonfeed.org/version/1');
    }
}