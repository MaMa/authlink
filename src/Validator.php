<?php

namespace Mama\Authlink;

class Validator
{
  private $config = array(
    'algo' => 'sha256'
  );

  public function __construct(array $config = null)
  {
    if ($config) {
      $this->config = array_merge($this->config, $config);
    }
  }

  public function validate($authlink)
  {

  }

}
