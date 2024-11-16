<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\PublicIPv4;

echo PublicIPv4::get();
