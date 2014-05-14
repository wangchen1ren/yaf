<?php

namespace Jos\Request;

class LbpOrderPrintDataGetRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.order.lbp.print.data.get';
  }

  public function setOrderId ($orderId) {
    $this->apiParas['order_id'] = $orderId;
    return $this;
  }
}