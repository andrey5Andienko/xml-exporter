<?php


require __DIR__ . '/vendor/autoload.php';

use App\Downloader\Downloader;
use App\Exporter\XmlExporter;
use GuzzleHttp\Client;

$downloader = new Downloader(new Client);

$content = (string) $downloader->download('laravel-news.com/feed/json')->current();

(new XmlExporter)->jsonToXml($content, 'files');