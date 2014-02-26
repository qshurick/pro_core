<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 26.02.14
 * Time: 17:52
 */

class Pro_Resource_DbPlugin extends Zend_Application_Resource_ResourceAbstract {

    const REGISTRY_ALIAS = 'db-plugins';

    /**
     * Strategy pattern: initialize resource
     *
     * @return mixed
     */
    public function init() {
        $this->getBootstrap()->bootstrap('logger'); // make sure that logger exists
        $options = $this->getOptions();
        /** @var $logger Zend_Log */
        $logger = Zend_Registry::get('logger')->ensureStream('system');
        $logger->log("Loading DbPlugins...", Zend_Log::DEBUG);

        $plugins = array();

        if (empty($options)) {
            $logger->log("DbPlugins not found", Zend_Log::DEBUG);
        } else {
            foreach($options as $alias => $className) {
                $message = "Load \"$alias\" from \"$className\"... ";
                if (class_exists($className)) {
                    $logger->log($message . "done", Zend_Log::DEBUG);
                    $plugins[$alias] = $className;
                } else {
                    $logger->log($message . "failed", Zend_Log::ERR);
                }

            }
        }
        Zend_Registry::set(self::REGISTRY_ALIAS, $plugins);
        return $plugins;
    }

}