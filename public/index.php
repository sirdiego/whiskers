<?php

require(__DIR__ . "/../vendor/autoload.php");

use Connect\Whiskers\Application;
use Connect\Whiskers\Filesystem\LocalFilesystem;
use Connect\Whiskers\Renderer\MustacheRenderer;

$base = realpath(__DIR__ . "/../");

$config = [
  "url" => "http://example.com",
  "paths"   => [
    "base"    => $base,
    "layouts" => $base . "/layouts",
    "pages"   => $base . "/pages",
    "public"  => $base . "/public"
  ],
  "layouts" => [
    "json"     => "json.mustache",
    "markdown" => "markdown.mustache"
  ],
  "meta"    => [
    "title" => "Whiskers"
  ]
];

$application = new Application(
  new LocalFilesystem(),
  new MustacheRenderer(),
  $config
);

$application->run();
