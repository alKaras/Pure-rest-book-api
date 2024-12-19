<?php

use application\App;

require __DIR__ . '/vendor/autoload.php';
header("Content-type: application/json; charset=UTF8");

(new App)::run();