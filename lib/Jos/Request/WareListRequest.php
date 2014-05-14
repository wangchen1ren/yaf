<?php

namespace Jos\Request;

class WareListRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.wares.list.get';
  }

  public function setFields ($fields) {
    $this->apiParas['fields'] = $fields;
    return $this;
  }

  public function setWareIds ($wareIds) {
    $this->apiParas['ware_ids'] = $wareIds;
    return $this;
  }
}