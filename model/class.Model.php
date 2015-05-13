<?php

require_once 'model/interface.Model.php';

class Model implements IModel
{
    protected $_application = null;
    protected $_data = array();

    public function __construct(&$application)
    {
        $this->_application = $application;
    }

    public function authorize()
    {
        return true;
    }

    public function process()
    {
    }

    public function getData()
    {
        return $this->_data;
    }

}

?>