<?php

namespace Mama\Authlink;

class Authlink
{

  private $config = array(
    'secret' => 'thisIsNotSecretSoChangeIt',
    'algo' => 'sha256',
    'lifetime' => 3600, // 1 hour in seconds
  );

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

    $timestamp = $this->getTimestamp($lifetime);

    $data = $timestamp;

    $hmac = $this->calculateHmac($data);

    $link = $data . ':' . $hmac;

    return $link;
  }

  public function validate($authlink)
  {
    $timestamp = $this->getTimestamp();

    list($data, $hmac) = explode(':', $authlink, 2);

    if ($this->calculateHmac($data) !== $hmac) {
      return false;
    }

    $linkTime = $data;

    if ($linkTime <= $this->getTimestamp()) {
      return false;
    }

    return true;
  }

  private function getTimestamp($lifetime = 0)
  {
    $time = time() + $lifetime;
    return date('YmdHis', $time);
  }

  private function calculateHmac($data)
  {
    return hash_hmac($this->config['algo'], $data, $this->config['secret']);
  }
}
