<?php

interface IDatabase
{
    public function connect($dbHost, $dbName, $dbUser, $dbPassword, $dbPort = '', $dbPresistant = false);
    public function disconnect();
    public function getData($query, $offset = 0, $limit = 0);
    public function getValue($query);
    public function modify($query);
    public function getServerInfo();

}

?>