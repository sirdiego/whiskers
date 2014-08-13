<?php

namespace Connect\Whiskers;

use CallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class Pages
{
  use ApplicationAware;

  /**
   * @var array
   */
  protected $pages;

  /**
   * @return Page
   */
  public function getLayout()
  {
    $context = $this->app["context"];

    $instance = new Page(
      new SplFileInfo(
        $context["paths"]["layouts"] . "/" . $context["layouts"][$context["layout"]]
      )
    );

    $instance->setApplication($this->app);

    return $instance;
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
        $instance = new Page($file);
        $instance->setApplication($this->app);

        $pages[] = $instance;
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
