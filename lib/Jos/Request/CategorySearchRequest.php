<?php

namespace Jos\Request;

class CategorySearchRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.warecats.get';
  }

  public function setFields ($fields) {
    $this->apiParas['fields'] = $fields;
    return $this;
  }
}