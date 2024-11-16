<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\PublicIPv6;

echo PublicIPv6::get();
