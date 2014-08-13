<?php

require("../vendor/autoload.php");

$application = new Connect\Whiskers\Application(
  require("../config.php")
);

$application->run();
