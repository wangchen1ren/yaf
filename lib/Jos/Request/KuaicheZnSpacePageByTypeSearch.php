<?php
namespace Jos\Request;

class KuaicheZnSpacePageByTypeSearch extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.zn.space.page.by.type.search';
  }

  public function setType ($type) {
    $this->apiParas['type'] = $type;
    return $this;
  }
}