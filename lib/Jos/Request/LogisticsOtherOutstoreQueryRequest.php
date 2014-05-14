<?php

namespace Jos\Request;

class LogisticsOtherOutstoreQueryRequest extends \Jos\JosRequest {

  public function getApiMethod() {
    return 'jingdong.logistics.otherOutstore.query';
  }

  public function setJoslOutboundNo($value) {
    return $this->apiParas['josl_outbound_no'] = $value;
    return $this;
  }
}