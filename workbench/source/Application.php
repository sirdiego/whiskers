<?php

namespace Connect\Whiskers;

class Application
{

  /**
   * @var Filesystem
   */
  protected $filesystem;

  /**
   * @var Renderer
   */
  protected $renderer;

  /**
   * @var array
   */
  protected $config;

  /**
   * @var array
   */
  protected $defaults = [
    "url"     => null,
    "paths"   => [
      "base"    => null,
      "layouts" => null,
      "pages"   => null,
      "public"  => null
    ],
    "layouts" => [
      "json"     => null,
      "markdown" => null
    ],
    "meta"    => [
      "title" => null
    ]
  ];

  /**
   * @param Filesystem $filesystem
   * @param Renderer   $renderer
   * @param array      $config
   */
  public function __construct(Filesystem $filesystem, Renderer $renderer, array $config = [])
  {
    $this->filesystem = $filesystem;
    $this->renderer   = $renderer;
    $this->config     = $config;
  }

  public function run()
  {
    $config = $this->merge($this->defaults, $this->config);
    $pages  = $this->getPages($config);

    print_r($pages);

    $pages    = [];
    $partials = [];
    $content  = "";
    $route    = "";
    $template = "";

    $data = [
      "config"   => $config,
      "pages"    => $pages,
      "partials" => $partials,
      "content"  => $content,
      "route"    => $route,
      "template" => $template
    ];

    $this->setHeaders($data);
    print $this->render($template, $data);
  }

  /**
   * @param string $template
   * @param array  $data
   *
   * @return string
   */
  protected function render($template, array $data)
  {
    return $this->renderer->render($template, $data);
  }

  /**
   * @param array $data
   */
  protected function setHeaders(array $data)
  {

  }

  /**
   * @param array $defaults
   * @param array $config
   *
   * @return array
   */
  protected function merge(array $defaults, array $config)
  {
    foreach ($config as $key => $value) {
      if (is_array($value)) {
        if (!isset($defaults[$key])) {
          $defaults[$key] = [];
        }

        $defaults[$key] = $this->merge($defaults[$key], $value);
      } else {
        $defaults[$key] = $config[$key];
      }
    }

    return $defaults;
  }

  /**
   * @param array $config
   *
   * @return array
   */
  protected function getPages(array $config)
  {
    $this->filesystem->setPathPrefix($config["paths"]["pages"]);

    $pages = [];
    $files = $this->filesystem->getFilesInPathRecursively();

    foreach ($files as $file) {
      if ($file["type"] === "file") {
        $pages[] = $this->formatPage($config, $file);
      }
    }

    return $pages;
  }

  /**
   * @param array $config
   * @param array $file
   *
   * @return array
   */
  protected function formatPage(array $config, array $file)
  {
    $parts = pathinfo($file["path"]);

    $page = [
      "path"      => $file["dirname"],
      "name"      => $parts["filename"],
      "extension" => $file["extension"],
      "modified"  => $file["timestamp"],
      "size"      => $file["size"]
    ];

    $page["url"] = "/" . $file["dirname"];

    if ($parts["filename"] !== "index") {
      $page["url"] .= "/" . $parts["filename"];
    }

    $page["url"] = $this->url($config, $page["url"]);

    return $page;
  }

  /**
   * @param array  $config
   * @param string $url
   *
   * @return string
   */
  protected function url(array $config, $url)
  {
    $url = str_replace("//", "/", $url);

    return rtrim($config["url"], "/") . $url;
  }
}
