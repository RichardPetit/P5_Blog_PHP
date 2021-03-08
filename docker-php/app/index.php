<?php

require_once __DIR__.'/vendor/autoload.php';

$routeCalled = $_SERVER['REQUEST_URI'];
echo 'Route called : '.$routeCalled;
