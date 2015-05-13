<?php

interface IView
{
    public function __construct(&$application, &$model);
    public function show();

}

?>