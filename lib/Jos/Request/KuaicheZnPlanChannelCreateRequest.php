<?php

namespace Jos\Request;

class KuaicheZnPlanChannelCreateRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.zn.plan.channel.create';
  }

  public function setPlanInfo ($info) {
    $this->apiParas['plan_info'] = $info;
    return $this;
  }
}