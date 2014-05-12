<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

class IndexController extends ApplicationController {
  protected $layout = 'blog';

  public function init() {
    parent::init();

    $this->getView()->setLayoutPath(
      $this->getConfig()->application->directory 
      . "/modules" . "/Blog" . "/views" . "/layouts"
    );
  }

  public function indexAction() {
    $this->heading = "Dashboard";

    $page = ($this->getRequest()->getParam('page')) ?: 0; //unused - see Bootstrap::_initRoutes

    $blog = new BlogModel();

    /*view*/
    $this->_view->entries = $blog->fetchAll();
    $this->_view->page = $page;

    /*layout*/
    //$this->_layout->meta_title = 'A Blog';
  }
}
