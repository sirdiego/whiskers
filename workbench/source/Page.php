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
          $this->template(),
          $this->app["context"]->toArray()
        );
      }
    }
  }

  /**
   * @return string
   */
  public function template()
  {
    if (!$this->template) {
      $delimiter = "---";
      $contents  = $this->content();

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
  public function content()
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
  public function context()
  {
    if (!$this->context) {
      $delimiter = "---";
      $contents  = $this->content();

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
  public function url()
  {
    return rtrim(
      $this->app["router"]->getHost() . "/" . $this->fragment(), "/"
    );
  }

  /**
   * @return string
   */
  public function fragment()
  {
    $path = $this->path();
    $name = $this->file->getBasename("." . $this->file->getExtension());

    if ($name !== "index") {
      return $path . "/" . $name;
    }

    return $path;
  }

  /**
   * @return string
   */
  public function path()
  {
    $path = str_replace(
      $this->app["context"]["paths"]["pages"], "", $this->file->getPath()
    );

    if ($path) {
      return $path;
    }

    return "/";
  }

  /**
   * @return SplFileInfo
   */
  public function file()
  {
    return $this->file;
  }
}
