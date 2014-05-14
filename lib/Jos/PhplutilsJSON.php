<?php

namespace Jos;

/**
 * 源码来源 https://code.google.com/p/phplutils/source/browse/trunk/JSON.class.php
 * 用于解决5.4以下php无JSON_BIGINT_AS_STRING的问题
 * sanwv为能正常使用对以下源码做过一些修改
 * sanwv 2013-12-13 
 */
class PhplutilsJSON {
  protected $t, $c;
  protected $debug;
  protected $useArrays = false;

  static public function decode($s, $array = false, $debug = false) {
    //return json_decode($s, true, 512, JSON_BIGINT_AS_STRING);
    //return json_decode($s, $array);

    // Version >= 5.4.x will suppoort JSON_BIGINT_AS_STRING parameter.
    if (version_compare(PHP_VERSION, '5.3.99', '>=')) {
      return json_decode($s, $array, 512, JSON_BIGINT_AS_STRING);
    }
    // Not supported JSON_BIGINT_AS_STRING so we will use our class.
    else {
      try {
        $d = new self($s);
        $d->useArrays = $array;
        $d->setDebug($debug);
        return $d->parseObject(512);
      } catch (\Exception $e) {
        return NULL;
      }
    }
  }

  static public function encode($object) {
    return json_encode($object);
  }

  public function __construct($s) {
    $this->setInput($s);
  }

  public function setInput($s) {
    $vv = array_map(function($a) { return isset($a[1]) ? $a[1] : $a; }, token_get_all('<?php ' . $s));
    $this->t = array_values(array_splice($vv, 1));
    $this->c = 0;
    if ($this->debug) print_r($this->t);
  }

  public function setDebug($debug) {
    $this->debug = $debug;
  }

  protected function getCurrentToken() {
    if ($this->c >= count($this->t)) throw(new \Exception("EOF"));
    return $this->t[$this->c];
  }

  protected function nextToken() {
    $this->c++;
  }

  protected function parseString() {
    $ct = $this->getCurrentToken();
    if ($ct[0] != '"') throw(new \Exception("JSON: Expecting String but found '" . $ct . "'"));
    $this->nextToken();
    //return stripcslashes(substr($ct, 1, -1));
    return json_decode($ct);
  }

  protected function expectValues($values = array()) {
    $ct = $this->getCurrentToken();
    foreach ($values as $value) if ($ct == $value) {
      $this->nextToken();
      return $ct;
    }
    throw(new \Exception("JSON: Expecting [" . implode(', ', array_map(function($a) { return "'{$a}'"; }, $values)) . "]"));
  }

  protected function parseNumber() {
    $ct = $this->getCurrentToken();
    if (!is_numeric($ct)) throw(new \Exception("JSON: Expecting a number but found '" . $ct . "'"));
    $this->nextToken();
    if ((string)(int)$ct == (string)$ct) {
      return (int)$ct;
    }
    return $ct;
  }

  protected function parseObject($depth = 512) {
    $ret = NULL;
    if ($depth == -1) throw(new \Exception("JSON: Depth too long"));
    $ct = $this->getCurrentToken();
    if ($this->debug) echo "--$ct\n";
    switch ($ct[0]) {
      // Object
    case '{':
      $ret = array();
      $this->nextToken();
      if ($this->getCurrentToken() != '}') {
        do {
          $key = $this->parseString();
          if ($this->debug) echo "---key:$key\n";

          $this->expectValues(array(':'));

          $value = $this->parseObject($depth - 1);
          if ($this->debug) echo "---value:$value\n";

          $ct = $this->expectValues(array(',', '}'));

          $ret[$key] = $value;
        } while ($ct == ',');
      } else {
        $this->nextToken();
      }
      if (!$this->useArrays) $ret = (object)$ret;
      break;
      // Array
    case '[':
      $ret = array();
      $this->nextToken();
      if ($this->getCurrentToken() != ']') {
        do {
          $value = $this->parseObject();
          if ($this->debug) echo "---value:$value\n";
          $ct = $this->expectValues(array(',', ']'));
          $ret[] = $value;
        } while ($ct == ',');
      } else {
        $this->nextToken();
      }
      //while ()
      break;
      // String
    case '"':
      $ret = $this->parseString();
      break;
    default:
      if (is_numeric($ct[0])) {
        $ret = $this->parseNumber();
      } else {
        switch ($ct) {
        case "null": $this->nextToken(); return NULL;
          //case "undefined": $this->nextToken(); return NULL;
        case "true": $this->nextToken(); return true;
        case "false": $this->nextToken(); return false;
        default: throw(new \Exception("JSON: Invalid object"));
        }
      }
      break;
    }
    return $ret;
  }
}

