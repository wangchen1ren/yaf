<?php

namespace Jos\Request;

class KuaicheAccountIncomeExpenseSearch extends \Jos\JosRequest {

  public function getApiMethod () {
    return 'jingdong.kuaiche.account.income_expense.search';
  }

  public function setCheckType ($checkType) {
    $this->apiParas['check_type'] = $checkType;
    return $this;
  }
}