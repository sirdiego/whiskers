<?php

$base = realpath(__DIR__ . "/..");

return [
  "paths"     => [
    "base"    => $base,
    "layouts" => $base . "/layouts",
    "pages"   => $base . "/pages",
    "public"  => $base . "/public"
  ],
  "layout"    => "default",
  "layouts"   => [
    "default" => "default.mustache"
  ],
  "meta"      => [
    "title" => "Whiskers"
  ],
  "renderers" => [
    "markdown" => new Connect\Whiskers\Renderer\MarkdownRenderer(),
    "mustache" => new Connect\Whiskers\Renderer\MustacheRenderer(),
    "mustdown" => new Connect\Whiskers\Renderer\MustdownRenderer()
  ],
  "plugins"   => [
    new Connect\Whiskers\Plugin\SamplePlugin()
  ]
];
