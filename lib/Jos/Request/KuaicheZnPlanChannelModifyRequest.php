<?php

namespace Jos\Request;

class KuaicheZnPlanChannelModifyRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.zn.plan.channel.modify';
  }

  public function setPlanInfo ($info) {
    $this->apiParas['plan_info'] = $info;
    return $this;
  }
}