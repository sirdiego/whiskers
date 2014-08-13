<?php

namespace Connect\Whiskers;

use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Page
{
  use ApplicationAware;

  /**
   * @var string
   */
  protected $contents;

  /**
   * @var array
   */
  protected $context;

  /**
   * @var string
   */
  protected $template;

  /**
   * @var SplFileInfo
   */
  protected $file;

  /**
   * @param SplFileInfo $file
   *
   * @return Page
   */
  public function __construct(SplFileInfo $file)
  {
    $this->file = $file;
  }

  /**
   * @return string
   */
  public function render()
  {
    foreach ($this->app["context"]["renderers"] as $extension => $engine) {
      if ($extension === $this->file->getExtension()) {
        $engine->setApplication($this->app);

        return $engine->render(
          $this->getTemplate(),
          $this->app["context"]->toArray()
        );
      }
    }
  }

  /**
   * @return string
   */
  public function getTemplate()
  {
    if (!$this->template) {
      $delimiter = "---";
      $contents  = $this->getContents();

      if (!strstr($contents, $delimiter)) {
        $this->template = $contents;
      } else {
        $parts          = explode($delimiter, $contents);
        $this->template = $parts[1];
      }
    }

    return $this->template;
  }

  /**
   * @return string
   */
  public function getContents()
  {
    if (!$this->contents) {
      $this->contents = file_get_contents(
        $this->file->getRealPath()
      );
    }

    return $this->contents;
  }

  /**
   * @return string
   */
  public function getContext()
  {
    if (!$this->context) {
      $delimiter = "---";
      $contents  = $this->getContents();

      if (!strstr($contents, $delimiter)) {
        $this->context = [];
      } else {
        $parts         = explode($delimiter, $contents);
        $this->context = Yaml::parse($parts[0]);
      }
    }

    return $this->context;
  }

  /**
   * @return string
   */
  public function getURL()
  {
    return $this->app["context"]["url"] . "/" . $this->getFragment();
  }

  /**
   * @return string
   */
  public function getFragment()
  {
    $path = $this->getPath();
    $name = $this->file->getBasename("." . $this->file->getExtension());

    if ($name !== "index") {
      return $path . "/" . $name;
    }

    return $path;
  }

  /**
   * @return string
   */
  public function getPath()
  {
    $path = str_replace(
      $this->app["context"]["paths"]["pages"], "", $this->file->getPath()
    );

    if ($path) {
      return $path;
    }

    return "/";
  }
}
