<?php

namespace Jos\Request;

class KuaicheZnKeywordgroupListSearchRequest extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.zn.keywordgroup.list.search';
  }

  public function setCategoryId ($category_id) {
    $this->apiParas['category_id'] = $category_id;
    return $this;
  }
}