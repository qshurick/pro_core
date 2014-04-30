<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 24.02.14
 * Time: 22:54
 */

abstract class Pro_Db_Table extends Zend_Db_Table_Abstract {

    /**
     * @var Zend_Log
     */
    protected $logger;
    protected $_plugins = array();

    public function init() {
        parent::init();
        $this->logger = Zend_Registry::get('logger')->ensureStream('system');
    }

    public function addPlugin(Pro_Db_Plugin_PluginAbstract $plugin) {
        if (!in_array($plugin, $this->_plugins))
            $this->_plugins[] = $plugin;
    }


    /**
     * @param bool $withFromPart
     * @return Pro_Db_Select
     */
    public function select($withFromPart = self::SELECT_WITH_FROM_PART) {
        $select = new Pro_Db_Select($this);
        if ($withFromPart == self::SELECT_WITH_FROM_PART) {
            $select->from($this->info(self::NAME), Pro_Db_Select::SQL_WILDCARD, $this->info(self::SCHEMA));
        }
        return $select;
    }

    public function insert(array $data) {
        $cols = $this->_getCols();
        $date = date('Y-m-d H:i:s');
        if (array_search('date_create', $cols)) {
            $data['date_create'] = $date;
        }
        if (array_search('date_update', $cols)) {
            $data['date_update'] = $date;
        }

        return parent::insert($data);
    }


    protected function _fetch(Zend_Db_Table_Select $select) {
        // Global plugins
        $plugins = Zend_Registry::isRegistered(Pro_Resource_DbPlugin::REGISTRY_ALIAS) ? Zend_Registry::get(Pro_Resource_DbPlugin::REGISTRY_ALIAS) : array();
        if (!empty($plugins)) {
            foreach($plugins as $alias => $className) {
                $this->logger->log("applying DbPlugin \"$alias\"", Zend_Log::DEBUG);
                /** @var $plugin Pro_Db_Plugin_PluginAbstract */
                $plugin = new $className();
                $plugin->beforeFetch($this->_name, $select);
            }
        }
        // Local plugins
        if (!empty($this->_plugins)) {
            foreach($this->_plugins as $plugin) {
                $plugin->beforeFetch($this->_name, $select);
            }
        }
        return parent::_fetch($select);
    }


}