<?php

require_once 'library/interface.Database.php';

class Database_MySql implements IDatabase
{
    private $_link = null;

    public function connect($dbHost, $dbName, $dbUser, $dbPassword, $dbPort = '', $dbPresistant = false)
    {
        if (!empty($dbPort)) {
            $dbHost .= ':' . $dbPort;
        }
        if (!$dbPresistant) {
            $this->_link = @mysql_connect($dbHost, $dbUser, $dbPassword);
        } else {
            $this->_link = @mysql_pconnect($dbHost, $dbUser, $dbPassword);
        }
        if (!$this->_link) {
            throw new Exception('Unable to connect the database.');
        }
        if (!mysql_select_db($dbName, $this->_link)) {
            throw new Exception('Unable to select the database.');
        }
    }

    public function disconnect()
    {
        if (!mysql_close($this->_link)) {
            throw new Exception('Unable to close the database connection.');
        }
    }

    public function getData($query, $offset = 0, $limit = 0)
    {
        if ($limit > 0) {
            $query .= " LIMIT $offset, $limit";
        }
        $data = array();
        if (!($result = mysql_query($query, $this->_link))) {
            throw new Exception(mysql_error($this->_link));
        }
        while ($row = mysql_fetch_object($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getValue($query)
    {
        if (!($result = mysql_query($query, $this->_link))) {
            throw new Exception(mysql_error($this->_link));
        }
        if (mysql_num_rows($result)) {
            return mysql_result($result, 0, 0);
        } else {
            return false;
        }
    }

    public function modify($query)
    {
        if (mysql_query($query, $this->_link)) {
            return mysql_affected_rows($this->_link);
        } else {
            throw new Exception(mysql_error($this->_link), 4);
        }
    }

    public function getServerInfo()
    {
        return 'MySQL ' . mysql_get_server_info($this->_link);
    }

}

?>