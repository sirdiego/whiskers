<?php

namespace Connect\Whiskers;

abstract class Renderer
{
  use ApplicationAware;

  /**
   * @param string $template
   *
   * @return string
   */
  abstract public function render($template);
}
