<?php

namespace Connect\Whiskers\Filesystem;

use Connect\Whiskers\Filesystem;
use League\Flysystem\Adapter\Local as Engine;
use League\Flysystem\Filesystem as Manager;

class LocalFilesystem implements Filesystem
{
  public function __construct()
  {
    $this->engine  = new Engine(__DIR__);
    $this->manager = new Manager($this->engine);
  }

  /**
   * @param string $prefix
   */
  public function setPathPrefix($prefix)
  {
    $this->engine->setPathPrefix($prefix);
  }

  /**
   * @param mixed $path
   *
   * @return array
   */
  public function getFilesInPath($path = null)
  {
    return $this->manager->listContents($path, false);
  }

  /**
   * @param mixed $path
   *
   * @return array
   */
  public function getFilesInPathRecursively($path = null)
  {
    return $this->manager->listContents($path, true);
  }
}
