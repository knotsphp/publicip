<?php

use SRWieZ\Native\MyIP\Fetchers\CurlFetcher;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;

DigFetcher::$isSupported = true;
CurlFetcher::$forceHTTP = true;
