<?php

namespace Jos\Request;

class LogisticsPoGetRequest extends \Jos\JosRequest {

  public function getApiMethod() {
    return 'jingdong.logistics.po.get';
  }

  public function setInboundNo($value) {
    return $this->apiParas['inbound_no'] = $value;
    return $this;
  }
}