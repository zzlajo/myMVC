<?php

require_once 'view/interface.View.php';

class View implements IView
{
    protected $_application = null;
    protected $_model = null;
    protected $_values = array();

    public function __construct(&$application, &$model)
    {
        $this->_application = $application;
        $this->_model = $model;
        $this->_values['tplPath'] = 'templates/';
        $this->_values['tplPath'] .= Settings::template . '/';
    }

    public function show()
    {
        ini_set('arg_separator.output', '&amp;');
        header('Content-Type: text/html; charset=utf-8');
        $tpl = $this->_values;
        require_once $this->_values['tplPath'] . 'tpl.Header.php';
        $_tplFile = 'tpl.' . $this->_application->getGo() . '.php';
        if ($this->_application->getGo() == '') {
            $_tplFile = 'tpl.Default.php';
        }
        @include_once $this->_values['tplPath'] . $_tplFile;
        require_once $this->_values['tplPath'] . 'tpl.Footer.php';
    }

}

?>