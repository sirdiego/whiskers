<?php

namespace Connect\Whiskers\Renderer;

use Connect\Whiskers\Renderer;
use Mustache_Engine;

class MustacheRenderer implements Renderer
{
  public function __construct()
  {
    $this->engine = new Mustache_Engine();
  }

  /**
   * @param string $template
   * @param array  $data
   *
   * @return string
   */
  public function render($template, array $data = [])
  {
    return $this->engine->render($template, $data);
  }
}
