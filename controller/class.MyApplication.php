<?php

require_once 'controller/class.Application.php';
require_once 'controller/class.Settings.php';
require_once 'library/class.DatabaseFactory.php';
include_once 'library/class.SessionHandler.php';

class MyApplication extends Application
{
    protected function _connectDatabase()
    {
       try {
            $this->_db = DatabaseFactory::Create(Settings::dbType);
            $this->_db->connect(
                Settings::dbHost,
                Settings::dbName,
                Settings::dbUser,
                Settings::dbPassword,
                Settings::dbPort
            );
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    protected function _startSession($name = '')
    {
        try {
            new SessionHandler($this->_db, 'sessions');
        } catch (Exception $e) {
            die($e->getMessage());
        }
        if (!empty($name)) {
            session_name($name);
        }
        session_start();
    }

    public static function isValidLanguage($language)
    {
        return is_dir('languages/' . basename($language));
    }

    protected function _initLanguage()
    {
        if (Settings::allowChangeLanguage) {
            if (isset($_GET['l'])) {
                $_SESSION['l'] = $_GET['l'];
            } elseif (isset($_SESSION['l'])) {
                $_SESSION['l'] = $_SESSION['l'];
            } elseif (isset($_COOKIE['l'])) {
                $_SESSION['l'] = $_COOKIE['l'];
            } else {
                $_SESSION['l'] = Settings::defaultLanguage;
            }
            if (!(self::isValidLanguage($_SESSION['l']))) {
                $_SESSION['l'] = Settings::defaultLanguage;
            }
            @setcookie('l', $_SESSION['l'], time() + 0xd2f00);
        } else {
            $_SESSION['l'] = Settings::defaultLanguage;
        }
        $this->_language = $_SESSION['l'];
    }

    protected function _initTimes()
    {
        $this->_now = time() + Settings::timeDifference;
        $this->_sqlNow = date('Y-m-d H:i:s', $this->_now);
    }

}

?>