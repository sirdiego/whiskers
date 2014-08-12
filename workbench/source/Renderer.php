<?php

namespace Connect\Whiskers;

interface Renderer
{
  /**
   * @param string $template
   * @param array  $data
   *
   * @return string
   */
  public function render($template, array $data = []);
}
