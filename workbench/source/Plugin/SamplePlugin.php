<?php

namespace Connect\Whiskers\Plugin;

use Connect\Whiskers\Plugin;

class SamplePlugin extends Plugin
{
  /**
   * @return void
   */
  public function onApplication()
  {
    echo "SamplePlugin: onApplication<br />";
  }

  /**
   * @return void
   */
  public function onRun()
  {
    echo "SamplePlugin: onRun<br />";
  }

  /**
   * @return void
   */
  public function onPath()
  {
    echo "SamplePlugin: onPath<br />";
  }

  /**
   * @return void
   */
  public function onPages()
  {
    echo "SamplePlugin: onPages<br />";
  }

  /**
   * @return void
   */
  public function onPage()
  {
    echo "SamplePlugin: onPage<br />";
  }

  /**
   * @return void
   */
  public function onPageContext()
  {
    echo "SamplePlugin: onPageContext<br />";
  }

  /**
   * @return void
   */
  public function onPageContent()
  {
    echo "SamplePlugin: onPageContent<br />";
  }

  /**
   * @return void
   */
  public function onHeaders()
  {
    echo "SamplePlugin: onHeaders<br />";
  }

  /**
   * @return void
   */
  public function onLayoutContent()
  {
    echo "SamplePlugin: onLayoutContent<br />";
  }
}
