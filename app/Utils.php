<?php

class Utils
{
  /**
 * htmlspecialcharsを略した関数
 */
  public static function h($str){
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
  }

  /**
 * URLパラメーターをチェックして、追加・削除・変更をする関数
 */
  public static function urlParamChange($par=Array(),$op=0){
    $url = parse_url($_SERVER["REQUEST_URI"]);
    // print_r($url);
    // exit;
    if(isset($url["query"])) {
      parse_str($url["query"],$query);
    } else {
      $query = Array();
    }
    foreach($par as $key => $value){
      if($key && is_null($value)) {
        unset($query[$key]);
      } else {
        $query[$key] = $value;
      }
    }
    $query = str_replace("=&", "&", http_build_query($query));
    $query = preg_replace("/=$/", "", $query);
    if ($query) {
      if (!$op) {
        $op = '?';
      } else {
        $op = '';
      }
      $query = $op.self::h($query);
    } else {
      $query = '';
    }
    return $query;
  }

  public $filmekbn;
  public $listname;

  public function __construct()
  {
    $this->filmekbn = filter_input(INPUT_GET, 'filmekbn');
    $this->listname = filter_input(INPUT_GET, 'listname');
  }
}