<?php

namespace Mama\Authlink;

require_once(__DIR__ . '/bootstrap.php');


class GeneratorTest extends \PHPUnit_Framework_TestCase
{
  public function testAuthlinkGenerated()
  {
    $generator = new Generator();

    $link = $generator->generate();

    $this->assertNotEmpty($link);
  }
}
