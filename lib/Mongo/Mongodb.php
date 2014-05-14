<?php

namespace Mongo;

class Mongodb {

  private static $_instance;

  private static function object_to_array($obj){
    return $obj;
    //return get_object_vars($obj);
    /*
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    $arr = array();
    foreach ($_arr as $key => $val){
      $val = (is_array($val)) || (is_object($val) ? object_to_array($val) : $val);
      $arr[$key] = $val;
    }
    return $arr;
     */
  }

  public static function getInstance() {
    if (! isset(self::$_instance)) {
      $conf = \Yaf\Registry::get('config')->mongodb;
      $server = $conf->server;
      $options = $conf->options->toArray();
      $mongo = new \MongoClient($server, $options);
      self::$_instance = $mongo->selectDB($conf->db);
    }
    return self::$_instance;
  }
}
