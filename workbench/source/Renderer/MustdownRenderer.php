<?php

namespace Connect\Whiskers\Renderer;

use Connect\Whiskers\Renderer;

class MustdownRenderer extends Renderer
{
  public function __construct()
  {
    $this->mustache = new MustacheRenderer();
    $this->markdown = new MarkdownRenderer();
  }

  /**
   * @param string $template
   *
   * @return string
   */
  public function render($template)
  {
    $this->mustache->setApplication($this->app);
    $this->markdown->setApplication($this->app);

    return $this->markdown->render(
      $this->mustache->render($template)
    );
  }
}
