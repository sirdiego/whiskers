<?php

namespace Connect\Whiskers;

use Illuminate\Container\Container;

class Application extends Container
{
  /**
   * @param array $context
   *
   * @return Application
   */
  public function __construct(array $context = [])
  {
    $this
      ->registerContext($context)
      ->registerPages()
      ->registerRouter()
      ->trigger("onApplication");
  }

  /**
   * @param string $event
   *
   * @return Application
   */
  protected function trigger($event)
  {
    foreach ((array) $this["context"]["plugins"] as $plugin) {
      if (method_exists($plugin, $event)) {
        $plugin->$event();
      }
    }

    return $this;
  }

  /**
   * @return Application
   */
  protected function registerRouter()
  {
    $this->bindShared("router", function () {
      return (new Router())
        ->setApplication($this);
    });

    return $this;
  }

  /**
   * @return Application
   */
  protected function registerPages()
  {
    $this->bindShared("pages", function () {
      return (new Pages())
        ->setApplication($this);
    });

    return $this;
  }

  /**
   * @param array $context
   *
   * @return Application
   */
  protected function registerContext(array $context)
  {
    $this->bindShared("context", function () use ($context) {
      return (new Context())
        ->extend($context)
        ->setApplication($this);
    });

    return $this;
  }

  /**
   * @return Application
   */
  public function run()
  {
    $this->trigger("onApplicationRun");

    $this["context"]["path"] = $path = $this["router"]->getPath();
    $this->trigger("onPath");

    $this["context"]["pages"] = $pages = $this["pages"]->getPages();
    $this->trigger("onPages");

    foreach ($pages as $page) {
      if ($path === $page->getFragment()) {
        $this
          ->extendContextWithPageInstance($page)
          ->trigger("onPage")
          ->extendContextWithPageContext($page)
          ->trigger("onPageContext")
          ->extendContextWithPageContent($page)
          ->trigger("onPageContent");
      }
    }

    $this
      ->setHeaders()
      ->trigger("onHeaders");

    $layout = $this["pages"]->getLayout();

    $this
      ->extendContextWithLayoutInstance($layout)
      ->trigger("onLayout")
      ->extendContextWithLayoutContent($layout)
      ->trigger("onLayoutContent");

    print $this["context"]["layout"]["content"];

    return $this;
  }

  /**
   * @param Page $page
   *
   * @return Application
   */
  protected function extendContextWithPageContent(Page $page)
  {
    $this["context"]->extend([
      "page" => [
        "content" => $page->render()
      ]
    ]);

    return $this;
  }

  /**
   * @param Page $page
   *
   * @return Application
   */
  protected function extendContextWithPageContext(Page $page)
  {
    $this["context"]->extend((array) $page->getContext());
    return $this;
  }

  /**
   * @param Page $page
   *
   * @return Application
   */
  protected function extendContextWithPageInstance(Page $page)
  {
    $this["context"]->extend([
      "page" => [
        "instance" => $page
      ]
    ]);

    return $this;
  }

  /**
   * @return Application
   */
  protected function setHeaders()
  {
    if (!isset($this["context"]["content-type"])) {
      $this["context"]["content-type"] = "text/html";
    }

    header("Content-type: " . $this["context"]["content-type"]);

    return $this;
  }

  /**
   * @param Page $layout
   *
   * @return Application
   */
  protected function extendContextWithLayoutInstance(Page $layout)
  {
    $this["context"]->extend([
      "layout" => [
        "instance" => $layout
      ]
    ]);

    return $this;
  }

  /**
   * @param Page $layout
   *
   * @return Application
   */
  protected function extendContextWithLayoutContent(Page $layout)
  {
    $this["context"]->extend([
      "layout" => [
        "content" => $layout->render()
      ]
    ]);

    return $this;
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
