<?php

namespace App\Exporter\Json;

class JsonExporter
{
    /** @var string */
    protected $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function save(string $filename)
    {
        $content = str_replace(["\n", "\r", "\t"], '', $this->content);
        $xml = simplexml_load_string($content);
        $json = json_encode($xml, JSON_UNESCAPED_SLASHES);

        file_put_contents("$filename.json", $json);
    }
}