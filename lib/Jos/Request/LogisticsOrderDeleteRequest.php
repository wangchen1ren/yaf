<?php

namespace Jos\Request;

class LogisticsOrderDeleteRequest extends \Jos\JosRequest {

  public function getApiMethod() {
    return 'jingdong.logistics.order.delete';
  }

  public function setReceiptNo($value) {
    return $this->apiParas['receipt_no'] = $value;
    return $this;
  }
}