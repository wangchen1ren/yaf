<?php

namespace Jos\Request;

class KuaicheZnScheduleBidDetailSearchRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.zn.schedule.bid.detail.search';
  }

  public function setPlanId ($planId) {
    $this->apiParas['plan_id'] = $planId;
    return $this;
  }
}