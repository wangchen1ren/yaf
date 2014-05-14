<?php

namespace Jos\Request;

class SoplOrderPrintDataGetRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.order.sopl.print.data.get';
  }

  public function setOrderId ($orderId) {
    $this->apiParas['order_id'] = $orderId;
    return $this;
  }
}