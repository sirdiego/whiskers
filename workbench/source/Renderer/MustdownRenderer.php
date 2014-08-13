<?php

namespace Connect\Whiskers\Renderer;

use Connect\Whiskers\Renderer;

class MustdownRenderer implements Renderer
{
  public function __construct()
  {
    $this->mustache = new MustacheRenderer();
    $this->markdown = new MarkdownRenderer();
  }

  /**
   * @param string $template
   * @param array  $data
   *
   * @return string
   */
  public function render($template, array $data = [])
  {
    return $this->markdown->render(
      $this->mustache->render($template, $data), $data
    );
  }
}
