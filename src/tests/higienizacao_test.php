<?php

use PHPUnit\Framework\TestCase;

final class HigienizacaoTest extends TestCase {

  public function testSanidade() {

    $oTmp = fHigienizaInput([
      'a' => ' ab<b>c</b>'
      ], false, [
      'a' => 'trim|sanitize_string'
      ]);

    $this->assertEquals('abc', $oTmp->aResultado['a']);

  }

  public function testPost() {

    $_POST = ['a' => 'ab<b>c</c> ']; //Simulamos a post

    $this->assertTrue(fHigienizaPost(false, [
      'a' => 'trim|sanitize_string'
    ]));

    $this->assertEquals('abc', $_POST['a']);

  }

  public function testGet() {

    $_GET = ['a' => 'ab<b>c</c> ']; //Simulamos a get

    $this->assertTrue(fHigienizaGet(false, [
      'a' => 'trim|sanitize_string'
    ]));

    $this->assertEquals('abc', $_GET['a']);

  }

  public function testGetError() {

    $_GET = ['a' => 'ab<b>c</c> ']; //Simulamos a get

    $this->assertFalse(fHigienizaGet([
      'a' => 'required|integer'
    ]));

    $this->assertEquals('The A field must be a number without a decimal', $_GET['a']);

  }

}
