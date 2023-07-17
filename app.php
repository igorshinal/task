<?php
namespace Controller;

use Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$config = require 'src/Configs/config.php';

$transactions = new Transaction($config);

$transactions->calculate($_SERVER['argv'][1]);