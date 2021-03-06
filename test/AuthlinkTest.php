<?php

namespace Mama\Authlink;

class AuthlinkTest extends \PHPUnit_Framework_TestCase
{

  public function testGenerate()
  {
    $authlink = new Authlink();

    $link = $authlink->generate();

    $this->assertNotEmpty($link);
  }

  public function testGenerateValidate()
  {
    $authlink = new Authlink();

    $link = $authlink->generate();

    $this->assertTrue($authlink->validate($link));
  }

  public function testExpiredLink()
  {
    $authlink = new Authlink(array('lifetime' => 1));

    $link = $authlink->generate();

    $this->assertTrue($authlink->validate($link));

    sleep(2); //wait to expire

    $this->assertFalse($authlink->validate($link));
  }

  public function testChecksum()
  {
    $authlink1 = new Authlink(array('secret' => 'someSecret'));
    $authlink2 = new Authlink('anotherSecret');

    $link1 = $authlink1->generate();
    $link2 = $authlink2->generate();

    $this->assertTrue($authlink1->validate($link1));
    $this->assertFalse($authlink1->validate($link2));

    $this->assertTrue($authlink2->validate($link2));
    $this->assertFalse($authlink2->validate($link1));
  }

  public function testWithExtra()
  {
    $authlink = new Authlink();

    $extra = 'Some text';

    $link = $authlink->generate($extra);

    $this->assertTrue($authlink->validate($link));
  }

  public function testChecksumWithExtra()
  {
    $authlink = new Authlink();

    $link1 = $authlink->generate('someExtra');
    $link2 = $authlink->generate('extraSome');

    $parts1 = $this->splitLink($link1);
    $parts2 = $this->splitLink($link2);

    $this->assertTrue($parts1['time'] === $parts2['time']);
    $this->assertFalse($parts1['hmac'] === $parts2['hmac']);

    $this->assertTrue($authlink->validate($link1));
    $this->assertTrue($authlink->validate($link2));
  }

  public function testMakeLink()
  {
    $Authlink = new Authlink(array(
      'secret' => 'MySuperSecretString',
      'lifetime' => 36000
    ));
    $link = $Authlink->generate(' Ihme töttöröö %!#""');
    $this->assertTrue($Authlink->validate($link));
  }

  public function testUrlEncodeSafe()
  {
    $link = (new Authlink())->generate();
    $this->assertTrue($link === urlencode($link));
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testInvalidConstructor()
  {
    new Authlink('');
  }

  private function splitLink($link)
  {
    list($data, $hmac) = explode(Authlink::CHECKSUM_DELIMITER, $link);
    list($time, $extra) = explode(Authlink::DATA_DELIMITER, $data);
    return array(
      'time' => $time,
      'extra' => $extra,
      'hmac' => $hmac
    );
  }

}
