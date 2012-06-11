<?php

include 'SplClassLoader.php';

$classLoader = new SplClassLoader('Titanium', __DIR__.'/lib');
$classLoader->register();