<?php

namespace Jos\Request;

class SopOrderPrintDataGetRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.order.sop.print.data.get';
  }

  public function setOrderId ($orderId) {
    $this->apiParas['order_id'] = $orderId;
    return $this;
  }
}