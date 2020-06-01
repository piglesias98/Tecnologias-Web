<?php
require_once('dbcredenciales.php');
class Database{
  private static $host = DB_HOST;
  private static $port = DB_PORT;
  private static $database = DB_NAME;
  private static $user = DB_USER;
  private static $password = DB_PASSWD;

  private function __construct(){}

  private static function connect(){
    $bbdd = new PDO('mysqli:host='.self::$host.';
                            port='.self::$port.';
                            dbname='.self::$database.';
                            charset=utf8',
                            self::$user, self::$password);
    return $bbdd;
  }


  public static function getInstance(){
    if(!self::$instance){
      self::$instance = self::connect();
    }
    return self::$instance;
  }

}

?>
