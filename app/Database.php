<?php

class Database
{
  private static $instans;

  public static function getInstans() {
    try {
      if(!isset(self::$instans)) {
        self::$instans = new PDO(
          DSN,
          DB_USER,
          DB_PASS,
          [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          ]
          );
      }
      return self::$instans;
    } catch(PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

}