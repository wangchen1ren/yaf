<?php

namespace Jos\Request;

class KuaicheBillDetailsSearchRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.bill.details.search';
  }

  public function setBillNo ($billNo) {
    $this->apiParas['bill_no'] = $billNo;
    return $this;
  }
}