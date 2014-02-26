<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 24.02.14
 * Time: 22:59
 */

class Pro_Db_Select extends Zend_Db_Table_Select {
    public function assemble() {
        $assembled = parent::assemble();
        /** @var $logger Logger_Application_Logger */
        $logger = Zend_Registry::get('logger');
        $logger->log(__CLASS__ . ":: " . $assembled, "system", Zend_log::DEBUG);

        return $assembled;
    }

}