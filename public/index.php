<?php

declare(strict_types=1);

const DS = DIRECTORY_SEPARATOR;

require_once dirname(path: __DIR__) . DS . 'vendor' . DS . 'autoload.php';

$main = Fidelify\Api\Main::create();
$main->run();
