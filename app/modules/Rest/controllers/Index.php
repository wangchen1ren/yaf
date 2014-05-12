<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

class IndexController extends RestfullController {

  public function init() {
      parent::init();
  }

  public function indexAction() {
      $this->context = "AAAAAAAAAAA";
  }

  public function testAction() {
    $this->context = "testtest";
  }

}
