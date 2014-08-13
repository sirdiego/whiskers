<?php

require("../vendor/autoload.php");

$application = new Connect\Whiskers\Application(
  require("context.php")
);

$application->run();
