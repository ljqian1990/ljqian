<?php
include_once dirname(__FILE__). '/vendor/autoload.php';

use Pressure\Classes\Process;
use Pressure\Classes\Parse;
use Pressure\Classes\Schema;

$oSchema = new Schema();
$oParse = new Parse($argv, $oSchema);

$client = (new ReflectionClass('Pressure\\Classes\\'. ucfirst($oParse->getSchema()) . 'client'))->newInstanceArgs([$oParse]);

$process = new Process($client, $oParse);
$process->start();