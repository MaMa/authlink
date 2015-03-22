<?php

namespace Mama\Authlink;

class Generator
{
  private $config = [
    'lifetime' => 3600, // 1 hour in seconds
  ];

  public function __construct(array $config = Null)
  {
    if ($config) {
      $this->config = array_merge($this->config, $config);
    }
  }

  public function generate($lifetime = Null)
  {
    if (!$lifetime) {
      $lifetime = $this->config['lifetime'];
    }

    return "fakelink";
  }

}
