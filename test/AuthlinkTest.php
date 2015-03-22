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

}
