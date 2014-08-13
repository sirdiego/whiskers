<?php

namespace Connect\Whiskers;

class Router
{

  /**
   * @var Application
   */
  protected $app;

  /**
   * @param Application $app
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   * @return string
   */
  public function getPath()
  {
    $uri    = explode("/", trim($this->getServerParameter("REQUEST_URI"), "/"));
    $script = explode("/", trim($this->getServerParameter("SCRIPT_NAME"), "/"));

    $parts = array_diff_assoc($uri, $script);

    if (empty($parts)) {
      return "/";
    }

    $path  = implode("/", $parts);
    $parts = explode("?", $path);

    return "/" . $parts[0];
  }

  /**
   * @param string $key
   * @param mixed  $default
   *
   * @return mixed
   */
  public function getServerParameter($key, $default = null)
  {
    if (isset($_SERVER[$key])) {
      return $_SERVER[$key];
    }

    return $default;
  }
}
