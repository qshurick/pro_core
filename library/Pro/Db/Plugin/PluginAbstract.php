<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 26.02.14
 * Time: 17:44
 */

abstract class Pro_Db_Plugin_PluginAbstract {
    /**
     * @var Logger_Application_Logger
     */
    protected $logger;

    public function __construct() {
        $this->logger = Zend_Registry::get('logger')->ensureStream('system');
        $this->init();
    }

    /**
     * Method calls in the end of the constructor
     */
    public function init() {}

    /**
     * @param string $tableName
     * @param Pro_Db_Select $select
     * @throws Pro_Db_Plugin_PluginException
     *
     * Method can update current Pro_Db_Select if necessary
     */
    public function beforeFetch($tableName, Zend_Db_Table_Select &$select) {}

}