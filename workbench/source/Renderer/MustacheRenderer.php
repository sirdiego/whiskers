<?php

namespace Connect\Whiskers\Renderer;

use Connect\Whiskers\Renderer;
use Mustache_Engine;

class MustacheRenderer extends Renderer
{
  public function __construct()
  {
    $this->engine = new Mustache_Engine();
  }

  /**
   * @param string $template
   *
   * @return string
   */
  public function render($template)
  {
    return $this->engine->render($template, $this->app["context"]);
  }
}
