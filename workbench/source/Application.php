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
    $this->registerContext($context);
    $this->registerPages();
    $this->registerRouter();

    $this->trigger("onApplication");
  }

  /**
   * @param array $context
   *
   * @return void
   */
  protected function registerContext(array $context)
  {
    $this->bindShared("context", function () use ($context) {
      $instance = new Context();
      $instance->setApplication($this);
      $instance->extend($context);

      return $instance;
    });
  }

  /**
   * @return void
   */
  protected function registerPages()
  {
    $this->bindShared("pages", function () {
      $instance = new Pages();
      $instance->setApplication($this);

      return $instance;
    });
  }

  /**
   * @return void
   */
  protected function registerRouter()
  {
    $this->bindShared("router", function () {
      $instance = new Router();
      $instance->setApplication($this);

      return $instance;
    });
  }

  /**
   * @return void
   */
  public function run()
  {
    $this->trigger("onRun");

    $this["context"]["path"]  = $path = $this["router"]->getPath();
    $this->trigger("onPath");

    $this["context"]["pages"] = $pages = $this["pages"]->getPages();
    $this->trigger("onPages");

    foreach ($pages as $page) {
      if ($path === $page->getFragment()) {
        $this->extendContextWithPageInstance($page);
        $this->extendContextWithPageContext($page);
        $this->extendContextWithPageContent($page);
        break;
      }
    }

    $this->setHeaders();
    $this->trigger("onHeaders");

    $this["context"]["content"] = $this["pages"]->getLayout()->render();
    $this->trigger("onLayoutContent");

    print $this["context"]["content"];
  }

  /**
   * @return void
   */
  protected function setHeaders()
  {
    if (!isset($this["context"]["content-type"])) {
      $this["context"]["content-type"] = "text/html";
    }

    header("Content-type: " . $this["context"]["content-type"]);
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

  /**
   * @param string $event
   *
   * @return void
   */
  protected function trigger($event)
  {
    foreach ((array) $this["context"]["plugins"] as $plugin) {
      if (method_exists($plugin, $event)) {
        $plugin->$event();
      }
    }
  }

  /**
   * @param Page $page
   *
   * @return void
   */
  protected function extendContextWithPageInstance(Page $page)
  {
    $this["context"]->extend([
      "page" => [
        "instance" => $page
      ]
    ]);

    $this->trigger("onPage");
  }

  /**
   * @param Page $page
   *
   * @return void
   */
  protected function extendContextWithPageContext(Page $page)
  {
    $this["context"]->extend((array) $page->getContext());
    $this->trigger("onPageContext");
  }

  /**
   * @param Page $page
   *
   * @return void
   */
  protected function extendContextWithPageContent(Page $page)
  {
    $this["context"]->extend([
      "page" => [
        "content" => $page->render()
      ]
    ]);

    $this->trigger("onPageContent");
  }
}
