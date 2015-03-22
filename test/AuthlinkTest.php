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
    $authlink = new Authlink();

    $link = $authlink->generate(1);

    $this->assertTrue($authlink->validate($link));

    sleep(1); //wait to expire

    $this->assertFalse($authlink->validate($link));
  }

  public function testChecksum()
  {
    $authlink1 = new Authlink(array('secret' => 'someSecret'));
    $authlink2 = new Authlink(array('secret' => 'anotherSecret'));

    $link1 = $authlink1->generate();
    $link2 = $authlink2->generate();

    $this->assertTrue($authlink1->validate($link1));
    $this->assertFalse($authlink1->validate($link2));

    $this->assertTrue($authlink2->validate($link2));
    $this->assertFalse($authlink2->validate($link1));
  }

}
