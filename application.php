#!/usr/bin/env php
<?php
namespace App;

require __DIR__.'/vendor/autoload.php';

use App\Command\ProcessFileCommand;
use Symfony\Component\Console\Application;

$app = new Application();

$app->add(new ProcessFileCommand());

$app->run();
