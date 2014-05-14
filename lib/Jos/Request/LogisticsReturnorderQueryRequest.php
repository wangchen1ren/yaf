<?php

namespace Jos\Request;

class LogisticsReturnorderQueryRequest  extends \Jos\JosRequest {

  public function getApiMethod() {
    return 'jingdong.logistics.returnorder.query';
  }

  public function setReceiptNo($value) {
    return $this->apiParas['receipt_no'] = $value;
    return $this;
  }

}