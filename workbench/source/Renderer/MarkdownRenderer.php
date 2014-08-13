<?php

namespace Connect\Whiskers\Renderer;

use Connect\Whiskers\Renderer;
use Michelf\MarkdownExtra;

class MarkdownRenderer implements Renderer
{
  /**
   * @param string $template
   * @param array  $data
   *
   * @return string
   */
  public function render($template, array $data = [])
  {
    return MarkdownExtra::defaultTransform($template);
  }
}
