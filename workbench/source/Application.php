<?php

namespace Connect\Whiskers;

use Illuminate\Container\Container;

class Application extends Container
{
  /**
   * @param array $context
   */
  public function __construct(array $context = [])
  {
    $this->registerContext($context);
    $this->registerPages();
    $this->registerRouter();
  }

  /**
   * @param array $context
   */
  protected function registerContext(array $context)
  {
    $this->bindShared("context", function () use ($context) {
      $instance = new Context($this);
      $instance->extend($context);

      return $instance;
    });
  }

  protected function registerPages()
  {
    $this->bindShared("pages", function () {
      return new Pages($this);
    });
  }

  protected function registerRouter()
  {
    $this->bindShared("router", function () {
      return new Router($this);
    });
  }

  public function run()
  {
    $this["context"]["path"]  = $path = $this["router"]->getPath();
    $this["context"]["pages"] = $pages = $this["pages"]->getPages();

    foreach ($pages as $page) {
      if ($path === $page->getFragment()) {
        $this["context"]->extend($page->getContext());
        $this["context"]["content"] = $page->render();
      }
    }

//    print_r($this["context"]["content"]);
//    exit();

    $this->setHeaders();

    print $this["pages"]->getLayout()->render();
  }

  protected function setHeaders()
  {

  }

  /**
   * @param string $template
   * @param array  $data
   *
   * @return string
   */
  protected function render($template, array $data)
  {
    return $this->renderer->render($template, $data);
  }
}
