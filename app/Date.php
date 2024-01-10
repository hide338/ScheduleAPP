<?php

class Date
{
  private $timestamp;
  private $listname;
  public $youbi;
  public $weekstart;
  public $weekend;
  public $monthstart;
  public $monthend;
  public $monthcount;
  public $monthFirstYoubi;

  public function __construct()
  {
    $this->listname = filter_input(INPUT_GET, 'listname');
    $this->timestamp();
  }

  /**
   * 今日の日付やURLパラムのセットされている日付の数値からtimestampを作成する関数
   */
  public function timestamp()
  {
    if(filter_input(INPUT_GET, 'ymd')){
      $ymd = filter_input(INPUT_GET, 'ymd');
    } else {
      $ymd = TODAY;
    }

    if ($this->timestamp === false || empty($this->timestamp)) {
      $this->timestamp = strtotime(TODAY);
    } else {
      $this->timestamp = date(strtotime($ymd));
    }

    return $this->timestamp;
  }

    /**
 * 現在の年度を西暦に変換して変数「$tnen」に格納
 */
  public function tnen() 
  {
    $year = date('Y', $this->timestamp);
    $month = date('m', $this->timestamp);
    if ($month < 4) {
      $tnen = $year - 1;
    } else {
      $tnen = $year;
    }
  return $tnen;
  }

  /**
   * 日別、週別、月別でprevとnextの値をセットする関数
   */
  public function flip($action) {
    // $timestamp = $this->timestamp();
    if ($action === 'prev') {
      switch($this->listname) {
        case 'day':
          $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp), date('d', $this->timestamp)-1, date('Y', $this->timestamp)));
          break;
        case 'week':
          $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp), date('d', $this->timestamp)-7, date('Y', $this->timestamp)));
          break;
        case 'month':
          $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp)-1, 1, date('Y', $this->timestamp)));
          break;
        default:
          $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp), date('d', $this->timestamp)-1, date('Y', $this->timestamp)));
          break;
      }
    } elseif ($action === 'next') {
      switch($this->listname) {
        case 'day':
          $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp), date('d', $this->timestamp)+1, date('Y', $this->timestamp)));
          break;
        case 'week':
          $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp), date('d', $this->timestamp)+7, date('Y', $this->timestamp)));
          break;
        case 'month':
          $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp)+1, 1, date('Y', $this->timestamp)));
          break;
        default:
          $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp), date('d', $this->timestamp)+1, date('Y', $this->timestamp)));
          break;
      }
    } elseif ($action === 'calendarprev') {
      $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp)-1, 1, date('Y', $this->timestamp)));
    } elseif ($action === 'calendarnext') {
      $flip = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp)+1, 1, date('Y', $this->timestamp)));
    } else {
      return;
    }

    return $flip;

  }

  /**
   * 選択日のカレンダーを作成する関数
   */
  public function createCalendar()
  {
    // $this->timestamp = $this->timestamp;
    // 該当月の日数を取得
    $count = date('t', $this->timestamp);

    // １日が何曜日か　0:日 1:月 2:火 ... 6:土
    // 方法１：mktimeを使う
    $youbi = date('w', mktime(0, 0, 0, date('m', $this->timestamp), 1, date('Y', $this->timestamp)));

    // カレンダー作成の準備weeks
    $weeks = [];
    $week = '';

    // 選択された日付けを取得
    $selectday = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp), date('d', $this->timestamp), date('Y', $this->timestamp)));

    // 第１週目：空のセルを追加
    // 例）１日が火曜日だった場合、日・月曜日の２つ分の空セルを追加する
    $week .= str_repeat('<td></td>', $youbi);
    for ( $day = 1; $day <= $count; $day++, $youbi++) {
      // 2021-06-3
      $date = date('Ymd', mktime(0, 0, 0, date('m', $this->timestamp), $day , date('Y', $this->timestamp)));
      //　日付のurl_paramをセットする
      $url_param = Utils::urlParamChange(array('ymd'=>$date));
      if (TODAY == $date) {
        // 今日の日付の場合は、class="today"をつける
        $week .= '<td class="today"><a class="d-block" href="'.$url_param.'">' . $day . '</a>';
      } elseif( $selectday == $date) {
        $week .= '<td class="selectday"><a class="d-block" href="'.$url_param.'">' . $day . '</a>';
      } else {
        $week .= '<td><a class="d-block" href="'.$url_param.'">' . $day . '</a>';
      }
      $week .= '</td>';
      // 週終わり、または、月終わりの場合
      if ($youbi % 7 == 6 || $day == $count) {
        if ($day == $count) {
          // 月の最終日の場合、空セルを追加
          // 例）最終日が水曜日の場合、木・金・土曜日の空セルを追加
          $week .= str_repeat('<td></td>', 6 - $youbi % 7);
        }
        // weeks配列にtrと$weekを追加する
        $weeks[] = '<tr>' . $week . '</tr>';
        // weekをリセット
        $week = '';
      }
    }

    // calendarレイアウトをreturnするために、$calendar変数を準備
    $calendar = '';
    // 週ごとに作られたweeks配列をループし、$calendar変数に格納
    foreach ($weeks as $week){
      $calendar .= $week;
    }

    return $calendar;

  }
}