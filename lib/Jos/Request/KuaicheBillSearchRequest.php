<?php

namespace Jos\Request;

class KuaicheBillSearchRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.bill.search';
  }

  public function setBillNo ($billNo) {
    $this->apiParas['bill_no'] = $billNo;
    return $this;
  }
}