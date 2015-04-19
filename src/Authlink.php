<?php

namespace Mama\Authlink;

class Authlink
{
  const CHECKSUM_DELIMITER = '-';
  const DATA_DELIMITER = '_';
  const TIME_FORMAT = 'ymdHis';
  const SEED = 'jCHM3ozmTtXzTkoEWFFCp1oLqjaZaEWfKTIlH6VVnpZ7H72a8OEzehwVgYIs';

  private $key = Null;

  private $config = array(
    'secret'   => '#-Shared-Secret-Can-Be-Here-Or-Given-As-Parameter-#',
    'algo'     => 'sha256',
    'lifetime' => 3600, // 1 hour in seconds
  );

  public function __construct($config = Null)
  {
    if (is_string($config) && trim($config)) {
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

  private function generateKey($secret)
  {
    $this->key = hash($this->config['algo'], self::SEED . $secret . self::SEED);
  }

  private function getKey()
  {
    if (!$this->key) {
      $this->generateKey($this->config['secret']);
    }
    return $this->key;
  }

  private function generateData($extra, $lifetime)
  {
    $lifetime = $lifetime ?: $this->config['lifetime'];
    $timestamp = $this->getTimestamp($lifetime);

    return $timestamp . self::DATA_DELIMITER . $this->sanitizeExtra($extra);
  }

  private function sanitizeExtra($extra)
  {
    return preg_replace('/[^a-zA-Z0-9]/','', trim($extra));
  }

  private function getTimestamp($lifetime = 0)
  {
    $lifetime = $lifetime < 0 ? 0 : intval($lifetime); //Cannot be negative
    return date(self::TIME_FORMAT, time() + $lifetime);
  }

  private function calculateHmac($data)
  {
    return self::baseUrlEncode(
      hash_hmac($this->config['algo'], $data, $this->getKey(), true));
  }

  private static function baseUrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', 'pS'), '=');
  }

}
