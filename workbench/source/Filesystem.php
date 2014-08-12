<?php

namespace Connect\Whiskers;

interface Filesystem
{
  /**
   * @param string $prefix
   */
  public function setPathPrefix($prefix);

  /**
   * @param mixed $path
   *
   * @return array
   */
  public function getFilesInPath($path = null);

  /**
   * @param mixed $path
   *
   * @return array
   */
  public function getFilesInPathRecursively($path = null);
}
