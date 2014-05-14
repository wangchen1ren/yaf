<?php

namespace Jos\Request;

class LogisticsOtherInstoreQueryRequest extends \Jos\JosRequest {

  public function getApiMethod() {
    return 'jingdong.logistics.otherInstore.query';
  }

  public function setInBoundNo($value) {
    return $this->apiParas['in_bound_no'] = $value;
    return $this;
  }
}