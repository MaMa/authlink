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

}
