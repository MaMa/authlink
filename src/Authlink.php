<?php

namespace Mama\Authlink;

class Authlink
{
  const CHECKSUM_DELIMITER = '|';
  const DATA_DELIMITER = ':';
  const TIME_FORMAT = 'ymdHis';

  private $config = array(
    'secret'   => '#!#-This-Is-Not-Secret-So-Change-It-#!#',
    'algo'     => 'sha256',
    'lifetime' => 3600, // 1 hour in seconds
  );

  public function __construct($config = Null)
  {
    if (is_string($config) && !empty(trim($config))) {
      $this->config['secret'] = trim($config);
    } elseif (is_array($config)) {
      $this->config = array_merge($this->config, $config);
    } elseif (!is_null($config)) {
      throw new \InvalidArgumentException('Constructor argument must be secret string or array of settings');
    }
  }

  public function generate($extra = Null, $lifetime = Null)
  {
    $data = $this->generateData($extra, $lifetime);
    $checksum = $this->calculateHmac($data);

    return $data . self::CHECKSUM_DELIMITER . $checksum;
  }

  public function validate($authlink)
  {
    // Validate hmac
    list($data, $hmac) = explode(self::CHECKSUM_DELIMITER, $authlink, 2);
    if ($this->calculateHmac($data) !== $hmac) {
      return false;
    }

    // Validate timestamp
    list($linkTime, $extra) = explode(self::DATA_DELIMITER, $authlink, 2);
    if ($linkTime < $this->getTimestamp()) {
      return false;
    }

    return true;
  }

  private function generateData($extra, $lifetime)
  {
    $extra = urlencode(trim(strval($extra)));
    $lifetime = $lifetime ?: $this->config['lifetime'];
    $timestamp = $this->getTimestamp($lifetime);

    return $timestamp . self::DATA_DELIMITER . $extra;
  }

  private function getTimestamp($lifetime = 0)
  {
    $lifetime = $lifetime < 0 ? 0 : intval($lifetime); //Cannot be negative
    return date(self::TIME_FORMAT, time() + $lifetime);
  }

  private function calculateHmac($data)
  {
    return self::base64url_encode(
      hash_hmac($this->config['algo'], $data, $this->config['secret'], true));
  }

  private static function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

}
