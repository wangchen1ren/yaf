<?php

namespace Jos\Request;

class SellerCatsGetRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.sellercats.get';
  }

  public function setFields ($fields) {
    $this->apiParas['fields'] = $fields;
    return $this;
  }
}