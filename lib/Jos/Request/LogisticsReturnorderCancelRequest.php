<?php

namespace Jos\Request;

class LogisticsReturnorderCancelRequest extends \Jos\JosRequest {

  public function getApiMethod() {
    return 'jingdong.logistics.returnorder.cancel';
  }

  public function setReceiptNo($value) {
    return $this->apiParas['receipt_no'] = $value;
    return $this;
  }

  public function setIsvSource($value) {
    return $this->apiParas['isv_source'] = $value;
    return $this;
  }
}