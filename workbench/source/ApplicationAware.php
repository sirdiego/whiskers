<?php

namespace Connect\Whiskers;

trait ApplicationAware
{

  /**
   * @var Application
   */
  protected $app;

  /**
   * @param Application $app
   *
   * @return void
   */
  public function setApplication(Application $app)
  {
    $this->app = $app;
  }
}
