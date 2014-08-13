<?php

namespace Connect\Whiskers;

use ArrayAccess;

class Context implements ArrayAccess
{

  /**
   * @var Application
   */
  protected $app;

  /**
   * @var array
   */
  protected $context = [];

  /**
   * @param Application $app
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   * @param array $context
   */
  public function extend(array $context)
  {
    $this->context = $this->merge($this->context, $context);
  }

  /**
   * @param array $base
   * @param array $extension
   *
   * @return array
   */
  protected function merge(array $base, array $extension)
  {
    foreach ($extension as $key => $value) {
      if (array_key_exists($key, $base) and is_array($value)) {
        $base[$key] = $this->merge($base[$key], $extension[$key]);
      } else {
        $base[$key] = $value;
      }
    }

    return $base;
  }

  /**
   * @param mixed $offset
   *
   * @return boolean
   */
  public function offsetExists($offset)
  {
    return isset($this->context[$offset]);
  }

  /**
   * @param mixed $offset
   *
   * @return mixed
   */
  public function offsetGet($offset)
  {
    return $this->context[$offset];
  }

  /**
   * @param mixed $offset
   * @param mixed $value
   */
  public function offsetSet($offset, $value)
  {
    $this->context[$offset] = $value;
  }

  /**
   * @param mixed $offset
   */
  public function offsetUnset($offset)
  {
    unset($this->context[$offset]);
  }

  /**
   * @return array
   */
  public function toArray()
  {
    return $this->context;
  }
}
