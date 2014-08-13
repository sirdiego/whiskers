<?php

namespace Connect\Whiskers;

use CallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class Pages
{

  /**
   * @var Application
   */
  protected $app;

  /**
   * @var array
   */
  protected $pages;

  /**
   * @param Application $app
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   * @return Page
   */
  public function getLayout()
  {
    $context = $this->app["context"];

    return new Page(
      $this->app, new SplFileInfo(
        $context["paths"]["layouts"] . "/" . $context["layouts"][$context["layout"]]
      )
    );
  }

  /**
   * @return array
   */
  public function getPages()
  {
    if (!$this->pages) {
      $pages = [];

      $files = $this->getFilesInPathWithExtensions(
        $this->app["context"]["paths"]["pages"], $this->getExtensions()
      );

      foreach ($files as $file) {
        $pages[] = new Page($this->app, $file);
      }

      $this->pages = $pages;
    }

    return $this->pages;
  }

  /**
   * @param string $path
   * @param array  $extensions
   *
   * @return array
   */
  protected function getFilesInPathWithExtensions($path, array $extensions)
  {
    $directory = new RecursiveDirectoryIterator($path);
    $recursive = new RecursiveIteratorIterator($directory);

    $filter = new CallbackFilterIterator($recursive, function ($item) use ($extensions) {
      return $item->isFile() and in_array($item->getExtension(), $extensions);
    });

    return iterator_to_array($filter);
  }

  /**
   * @return array
   */
  public function getExtensions()
  {
    return array_keys(
      (array) $this->app["context"]["renderers"]
    );
  }
}
