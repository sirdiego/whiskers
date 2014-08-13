<?php

namespace Connect\Whiskers;

class Router
{
  use ApplicationAware;

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

  /**
   * @return string
   */
  public function getHost()
  {
    return $this->getProtocol() . "://" . $_SERVER["HTTP_HOST"];
  }

  /**
   * @return string
   */
  protected function getProtocol()
  {
    $protocol = "http";

    if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] !== "off") {
      $protocol = "https";
    }

    return $protocol;
  }
}
