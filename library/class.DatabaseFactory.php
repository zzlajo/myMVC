<?php

class DatabaseFactory
{
    public static function Create($type)
    {
        $className = 'Database_' . $type;
        $fileName = 'class.' . $className . '.php';
        if (file_exists(dirname(__FILE__) . '/' . $fileName)) {
            require_once $fileName;
            return new $className;
        } else {
            return null;
        }
    }

}
?>