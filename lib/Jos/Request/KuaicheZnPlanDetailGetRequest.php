<?php

namespace Jos\Request;

class KuaicheZnPlanDetailGetRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.zn.plan.detail.get';
  }

  public function setPlanId ($id) {
    $this->apiParas['plan_id'] = $id;
    return $this;
  }
}