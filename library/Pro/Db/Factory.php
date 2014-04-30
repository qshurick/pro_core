<?php
/**
 * Created by PhpStorm.
 * User: laslo
 * Date: 27/04/14
 * Time: 20:28
 */

class Pro_Db_Factory {
    /**
     * @var Pro_Db_Table
     */
    protected $_table;

    /**
     * @var Pro_Db_Select
     */
    protected $_select;

    public function __construct(Pro_Db_Table $table = null) {
        if (!empty($table)) {
            $this->setTable($table);
        }
        $this->init();
    }

    public function init() {

    }

    public function setTable(Pro_Db_Table $table) {
        $this->_table = $table;
        $this->_select = $this->_table->select();
    }

    /**
     * @return Pro_Db_Select
     */
    public function select() {
        return $this->_select;
    }

    /**
     * @return Pro_List
     */
    public function getList() {
        $rows = $this->_table->fetchAll($this->_select);
        $array = $rows->toArray();
        $list = new Pro_List($array);
        return $list;
    }

    /**
     * @param $page
     * @param $perPage
     * @return Zend_Paginator
     */
    public function getPager($page, $perPage) {
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($this->_select);
        $pager = new Zend_Paginator($adapter);
        $pager->setCurrentPageNumber($page);
        $pager->setItemCountPerPage($perPage);
        return $pager;
    }
} 