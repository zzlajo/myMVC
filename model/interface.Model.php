<?php

interface IModel
{
    public function __construct(&$application);
    public function authorize();
    public function process();
    public function getData();

}

?>