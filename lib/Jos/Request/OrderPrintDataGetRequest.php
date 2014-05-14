<?php

namespace Jos\Request;

class OrderPrintDataGetRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.order.print.data.get';
  }

  public function setOrderId ($orderId) {
    $this->apiParas['order_id'] = $orderId;
    return $this;
  }
}