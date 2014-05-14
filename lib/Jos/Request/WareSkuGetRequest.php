<?php

namespace Jos\Request;

class WareSkuGetRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.ware.sku.get';
  }

  public function setFields ($fields) {
    $this->apiParas['fields'] = $fields;
    return $this;
  }

  public function setSkuId ($skuId) {
    $this->apiParas['sku_id'] = $skuId;
    return $this;
  }
}