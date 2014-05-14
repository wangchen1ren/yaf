<?php

namespace Jos\Request;

class KuaicheZnSpaceInfoSearchRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.zn.space.info.search';
  }

  public function setPageId ($pageId) {
    $this->apiParas['page_id'] = $pageId;
    return $this;
  }
}