<?php
class Session{
    public static function init(){
        session_start();
    }
    public static function get($key){
        return isset($_SESSION[$key]) ? $_SESSION[$key] :'';
    }
    public static function set($key,$value){
        $_SESSION[$key]= $value;
    }
    public static function unset($key){
        unset($_SESSION[$key]);
    }
    public static function destroy(){
        session_destroy();
    }
}