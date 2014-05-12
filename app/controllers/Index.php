<?php

class IndexController extends ApplicationController {
  protected $layout = 'frontend';

  public function indexAction($name = 'AAA') {
    $this->heading = 'Home Page';

    echo "Request: " . var_export($this->getRequest(), true) . "</br>";

    echo "Query: " . var_export($this->getRequest()->getQuery(), true) . "</br>";

    //1. fetch query
    $get = $this->getRequest()->getQuery("get", "default value");

    //2. fetch model
    $model = new SampleModel();

    //3. assign
    $this->getView()->assign("content", $model->selectSample());
    $this->getView()->assign("name", $name);

    //4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
    return TRUE;

  }
}
