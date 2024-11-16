<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\PublicIP;

echo PublicIP::get();
