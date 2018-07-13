<?php

namespace App\Downloader;

use Generator;
use GuzzleHttp\ClientInterface;

class Downloader implements DownloaderInterface
{
    /** @var ClientInterface */
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function download(string ...$urls): Generator
    {
        foreach ($urls as $url) {
            yield $this->client->request('GET', $url)->getBody();
        }
    }
}
