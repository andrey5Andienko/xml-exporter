<?php

namespace App\Downloader;

use Generator;

interface DownloaderInterface
{
    public function download(string ...$urls): Generator;
}
