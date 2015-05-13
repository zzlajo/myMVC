<?php
if (class_exists('SessionHandler') != true) {
class SessionHandler
{
    private $_db = null;
    private $_table = '';

    public function __construct($db, $table)
    {
        if (!($this->_db = $db)) {
            throw new Exception('Invalid database connection.');
        }
        if (($this->_table = trim($table)) == '') {
            throw new Exception('Invalid session table name.');
        }
        session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
    }

    public function open($savePath, $sessName)
    {
        return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        try {
            return $this->_db->getValue("SELECT data FROM $this->_table WHERE id = '$id'");
        } catch (Exception $e) {
            return '';
        }
    }

    public function write($id, $data) {
        try {
            $this->_db->modify("DELETE FROM $this->_table WHERE id = '$id'");
            $affectedRows = $this->_db->modify("INSERT INTO $this->_table (id, access, data) VALUES ('$id', '" . time() . "', '$data')");
            return ($affectedRows ? true : false);
        } catch (Exception $e) {
            return false;
        }
    }

    public function destroy($id) {
        try {
            $affectedRows = $this->_db->modify("DELETE FROM $this->_table WHERE id = '$id'");
            return ($affectedRows ? true : false);
        } catch (Exception $e) {
            return false;
        }
    }

    public function gc($maxLifeTime){
        $timeout = time() - $maxLifeTime;
        try {
            return $this->_db->modify("DELETE FROM $this->_table WHERE access < '$timeout'");
        } catch (Exception $e) {
            return false;
        }
    }

}
}
?>