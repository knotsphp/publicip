<?php

use KnotsPHP\PublicIP\Fetchers\CurlFetcher;
use KnotsPHP\PublicIP\Fetchers\DigFetcher;

DigFetcher::$isSupported = true;
CurlFetcher::$forceHTTP = true;
