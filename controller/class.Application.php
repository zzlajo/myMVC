<?php

require_once 'controller/interface.Application.php';

class Application implements IApplication
{
    protected $_db = null;
    protected $_language = '';
    protected $_go = '';
    protected $_selfUrl = '';
    protected $_now = '';
    protected $_sqlNow = '';

    public function getLanguage()
    {
        return $this->_language;
    }

    public function getGo()
    {
        return $this->_go;
    }

    public function getSelfUrl()
    {
        return $this->_selfUrl;
    }

    public function getNow()
    {
        return $this->_now;
    }

    public function getSqlNow()
    {
        return $this->_sqlNow;
    }

    protected function _connectDatabase()
    {
    }

    protected function _startSession($name = '')
    {
        if (!empty($name)) {
            session_name($name);
        }
        session_start();
    }

    protected function _initLanguage()
    {
    }

    public static function buildGoUrl($go)
    {
        $url = 'https://';
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
            $url = 'http://';
        }
        $url .= $_SERVER['HTTP_HOST'];
        $url .= dirname($_SERVER['PHP_SELF']);
        $url = trim(str_replace('\\', '/', $url));
        if (substr($url, -1) != '/') {
            $url .= '/';
        }
        if (!empty($go)) {
            $url .= '?go=' . $go;
        }
        return $url;
    }

    protected function _initSelfUrl()
    {
        $this->_selfUrl = self::buildGoUrl($this->_go);
    }

    protected function _initTimes()
    {
        $this->_now = time();
        $this->_sqlNow = date('Y-m-d H:i:s', time());
    }

    public function init()
    {
        $this->_connectDatabase();
//        $this->_startSession();
        $this->_initLanguage();
        $this->_initSelfUrl();
        $this->_initTimes();
    }

    public function run()
    {
        $this->_go = (isset($_GET['go']) ? basename($_GET['go']) : '');

        if (!empty($this->_language)) {
            $langClass =  'Language_' . $this->_go;
            $langFile = 'languages/' . $this->_language . '/class.' . $langClass . '.php';
            if (!file_exists($langFile)) {
                $langClass =  'Language';
                $langFile = 'languages/' . $this->_language . '/class.' . $langClass . '.php';
            }
            require_once $langFile;
        }

        $modelClass =  'Model_' . $this->_go;
        $modelFile = 'model/class.' . $modelClass . '.php';
        if (!file_exists($modelFile)) {
            $modelClass =  'Model';
            $modelFile = 'model/class.' . $modelClass . '.php';
        }
        require_once $modelFile;
        $model = new $modelClass($this);
        if (!$model->authorize()) {
            die('You have no permission to view this page.');
        }
        $model->process();

        $viewClass =  'View_' . $this->_go;
        $viewFile = 'view/class.' . $viewClass . '.php';
        if (!file_exists($viewFile)) {
            $viewClass =  'View';
            $viewFile = 'view/class.' . $viewClass . '.php';
        }
        require_once $viewFile;
        $view = new $viewClass($this, $model);
        $view->show();
    }

}

?>