<?php


require __DIR__ . '/vendor/autoload.php';

use App\Downloader\Downloader;
use App\Exporter\XmlExporter;
use GuzzleHttp\Client;

$downloader = new Downloader(new Client);

$content = $downloader->download('laravel-news.com/feed/json');

foreach ($content as $item) {
    $jsons = json_decode((string)$item);
}

XmlExporter::jsonToXml($jsons, 'file');
