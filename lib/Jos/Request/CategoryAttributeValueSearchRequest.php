<?php

namespace Jos\Request;

class CategoryAttributeValueSearchRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return '360buy.ware.get.attvalue';
  }

  public function setAvs ($avs) {
    $this->apiParas['avs'] = $avs;
    return $this;
  }

  public function setFields ($fields) {
    $this->apiParas['fields'] = $fields;
    return $this;
  }
}