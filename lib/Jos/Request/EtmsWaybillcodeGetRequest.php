<?php

namespace Jos\Request;

class EtmsWaybillcodeGetRequest extends \Jos\JosRequest {

  public function getApiMethod() {
    return 'jingdong.etms.waybillcode.get';
  }

  public function setPreNum($value) {
    return $this->apiParas['preNum'] = $value;
    return $this;
  }

  public function setCustomerCode($value) {
    return $this->apiParas['customerCode'] = $value;
    return $this;
  }
}