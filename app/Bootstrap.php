<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

use eYaf\Request;
use eYaf\Layout;

class Bootstrap extends \Yaf\Bootstrap_Abstract {

    public function _initSession(\Yaf\Dispatcher $dispatcher) {
        \Yaf\Session::getInstance()->start();
    }

    public function _initErrorHandler(Yaf\Dispatcher $dispatcher) {
        $dispatcher->setErrorHandler(array(get_class($this),'error_handler'));
    }

    public function _initConfig(Yaf\Dispatcher $dispatcher) {
        $this->config = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('config', $this->config);
        //\Yaf\Loader::import(APP_PATH . '/config/defines.inc.php');
    }

    /*
     * initIncludePath is only required because zend components have a shit load of
     * include_once calls everywhere. Other libraries could probably just use
     * the autoloader (see _initNamespaces below).
     */
    public function _initIncludePath() {
        set_include_path(
            get_include_path()
            . PATH_SEPARATOR
            . $this->config->application->library->directory);
    }

    public function _initNamespaces(){
        //\Yaf\Loader::getInstance()->registerLocalNameSpace(array("Zend"));
        \Yaf\Loader::getInstance()
            ->registerLocalNameSpace(array(
                'Zend',
                'Jos',
                'Mongo',
            ));
    }

    /*
    public function _initJingdong() {
        if (Yaf\Application::app()->environ() == 'product') {
            define('JOS_ENV', 'product');
        }
        define('JOS_APP_KEY', $this->config->jingdong->appkey);
        define('JOS_SECRET_KEY', $this->config->jingdong->secretkey);
        define('JOS_REDIRECT_URI', $this->config->jingdong->redirect_uri);
        define('JOS_ACCESS_TOKEN', $this->config->jingdong->access_token);
    }
     */

    public function _initRequest(Yaf\Dispatcher $dispatcher) {
        $dispatcher->setRequest(new Yaf\Request\Http());
        //$dispatcher->setRequest(new Request());
    }

    public function _initDatabase(Yaf\Dispatcher $dispatcher) {
    }

    public function _initDefaultDbAdapter(){
        $dbAdapter = new Zend_Db_Adapter_Pdo_Sqlite(
            $this->config->blog->database->params->toArray()
        );  

        Zend_Db_Table::setDefaultAdapter($dbAdapter);
    } 

    public function _initPlugins(Yaf\Dispatcher $dispatcher) {
        $dispatcher->registerPlugin(new LogPlugin());

        $this->config->application->protect_from_csrf &&
            $dispatcher->registerPlugin(new AuthTokenPlugin());
    }

    public function _initLoader(Yaf\Dispatcher $dispatcher) {
    }

    public function _initRoute(Yaf\Dispatcher $dispatcher) {
        $config = new Yaf\Config\Ini(APP_PATH . '/config/routing.ini');
        $dispatcher->getRouter()->addConfig($config);
    }

    /**
     * Custom init file for modules.
     *
     * Allows to load extra settings per module, like routes etc.
     */
    public function _initModules(Yaf\Dispatcher $dispatcher) {
        $app = $dispatcher->getApplication();

        $modules = $app->getModules();
        foreach ($modules as $module) {
            if ('index' == strtolower($module)) continue;

            require_once $app->getAppDirectory() . "/modules" . "/$module" . "/_init.php";
        }
    }

    public function _initLayout(Yaf\Dispatcher $dispatcher) {
        $layout = new Layout($this->config->application->layout->directory);
        $dispatcher->setView($layout);
    }

    /**
     * Custom error handler.
     *
     * Catches all errors (not exceptions) and creates an ErrorException.
     * ErrorException then can caught by Yaf\ErrorController.
     *
     * @param integer $errno   the error number.
     * @param string  $errstr  the error message.
     * @param string  $errfile the file where error occured.
     * @param integer $errline the line of the file where error occured.
     *
     * @throws ErrorException
     */
    public static function error_handler($errno, $errstr, $errfile, $errline) {
        // Do not throw exception if error was prepended by @
        //
        // See {@link http://www.php.net/set_error_handler}
        //
        // error_reporting() settings will have no effect and your error handler 
        // will be called regardless - however you are still able to read 
        // the current value of error_reporting and act appropriately. 
        // Of particular note is that this value will be 0 
        // if the statement that caused the error was prepended 
        // by the @ error-control operator.
        //
        if (error_reporting() === 0) return;

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
