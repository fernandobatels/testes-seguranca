<?php

use PHPUnit\Framework\TestCase;

final class EscapeTest extends TestCase {

  public function testSanidade() {
    $this->assertEquals(fE('sanidade'), 'sanidade');
  }

  public function testHtml() {
    $this->assertEquals(fE('sani<b>d<b>ade'), 'sani&lt;b&gt;d&lt;b&gt;ade');
  }

  public function testJs() {
    $this->assertEquals(fE('<script>alert("!!!")</script>'), '&lt;script&gt;alert(&quot;!!!&quot;)&lt;/script&gt;');
  }




}
