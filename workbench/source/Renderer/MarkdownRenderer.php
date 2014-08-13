<?php

namespace Connect\Whiskers\Renderer;

use Connect\Whiskers\Renderer;
use Michelf\MarkdownExtra;

class MarkdownRenderer extends Renderer
{
  /**
   * @param string $template
   *
   * @return string
   */
  public function render($template)
  {
    return MarkdownExtra::defaultTransform($template);
  }
}
