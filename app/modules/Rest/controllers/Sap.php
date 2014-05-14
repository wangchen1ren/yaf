<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

class SapController extends RestfulController {

    const MONGO_TABLE_NAME = 'wares';

    private $mongo_ware;

    public function init() {
        parent::init();
        $db = \Mongo\Mongodb::getInstance();
        $this->mongo_ware = $db->selectCollection(self::MONGO_TABLE_NAME);
    }

    public function refreshtokenAction() {
        $jos = \Jos\JosClient::getInstance();
    }

    public function fetchallAction() {
        $query = $this->getRequest()->getQuery();
        $page_size = isset($query['page_size']) ? $query['page_size'] : self::PAGE_SIZE;
        $got = 0;
        for ($page = 1; ; $page++) {
            $res = $this->_fetchWares($page, $page_size);
            $total = $res['total'];
            $wares = $res['ware_infos'];
            $got += count($wares);
            if ($got >= $total) {
                break;
            }
        }
        $this->context = $got;
    }

    public function fetchAction() {
        $query = $this->getRequest()->getQuery();
        $page = isset($query['page']) ? $query['page'] : 1;
        $page_size = isset($query['page_size']) ? $query['page_size'] : self::PAGE_SIZE;
        $res = $this->_fetchWares($page, $page_size);
        $total = $res['total'];
        $wares = $res['ware_infos'];

        $this->context = count($wares);
    }

    private function _fetchWares($page, $page_size) {
        $jos = \Jos\JosClient::getInstance();
        $req = new \Jos\Request\WareInfoByInfoRequest();
        $req->setPage($page);
        $req->setPageSize($page_size);
        $res = $jos->execute($req);

        if ($res['code'] != 0) {
            // error
        }
        $total = $res['total'];
        $wares = $res['ware_infos'];
        $this->_saveWares($wares);

        return $res;
    }

    private function _saveWares($wares) {
        foreach ($wares as $ware) { 
            $query = array('jd_ware_id' => $ware['ware_id']);
            $row = $this->table->findOne($query);
            $new_row = ($row == null) ? array() : $row;
            foreach ($ware as $key => $value) {
                $new_row['jd_' . $key] = $value;
            }
            if ($row == null) {
                $res = $this->table->insert($new_row);
            } else {
                $res = $this->table->update($query, $new_row);
            }
        }
    }

    public function dbAction() {
        $query = $this->getRequest()->getQuery();
        var_dump($query);
        $cursor = $this->table->find($query);
        $all = array();
        foreach ($cursor as $doc) {
            $all[] = $doc;
        }
        $this->context = json_encode($all);
    }

    public function testAction() {
        $this->context = "testtest";
    }

}
