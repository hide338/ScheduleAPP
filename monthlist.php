<?php
  // list($termstart, $termend, $termdingleday) = $term;
  // $youbiFirst = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
  // $youbiEnd = date('w', mktime(0, 0, 0, date('m', $timestamp), date('d', strtotime($termend)), date('Y', $timestamp)));
  // $dayCount = date('t', $timestamp);
  // $weekCount = ($youbiFirst + $dayCount + (6 - $youbiEnd)) / 7;

/**
 * カレンダー作成
 */
// カレンダー作成の準備
$weeks = [];
$week = '';
// $youbiをmonthレイアウトでも使うため、カウントをリセットする
$youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
// echo $count;
// 月の日数をカウントする
$count = date('t', $timestamp);

// 第１週目：空のセルを追加
// 例）１日が火曜日だった場合、日・月曜日の２つ分の空セルを追加する
$week .= str_repeat('<td></td>', $youbi);
for ( $day = 1; $day <= $count; $day++, $youbi++) {
  // 2021-06-3
  $setday = date('Ymd', mktime(0, 0, 0, date('m', $timestamp), $day , date('Y', $timestamp)));
  // scheduleを取得するためにscheduleインスタンスを作成
  // $schedule = new Schedule($pdo);
  $schedules = $schedule->getSchedules($tnen, $setday);
  //　日付のurl_paramをセットする
  $urlDaylist = Utils::urlParamChange(array('ymd'=>$setday, 'listname'=>'day'));
  if (TODAY == $setday) {
    // 今日の日付の場合は、class="today"をつける
    $week .= '<td><a class="d-block today el_day" href="'.$urlDaylist.'">' . $day . '</a><div class="js_monthlist bl_monthlist" data-timestamp="'. $setday .'">';
  } else {
    $week .= '<td><a class="d-block el_day" href="'.$urlDaylist.'">' . $day . '</a><div class="js_monthlist bl_monthlist" data-timestamp="'. $setday .'">';
  }

  for ($i = 0; $i < count($schedules); $i++) {
    // if ($setday === $schedules[$i]['ymd']) {
      $ymd = $schedules[$i]['ymd'];
      $sttime = trim($schedules[$i]["sttime"]);
      if($sttime === '') {
        $sttime = '';
      } else {
        $sttimeH = substr($schedules[$i]["sttime"], 0, 2);
        $sttimeM = substr($schedules[$i]["sttime"], 2, 2);
        $sttime = "{$sttimeH}:{$sttimeM}";
      }
      $sckb = $schedules[$i]['sckb'];
      $kancd = $schedules[$i]['kancd'];
      $kjnam = $schedules[$i]['kjnam'];
      $sctitle = $schedules[$i]['sctitle'];
      $tantonam = $schedules[$i]['tantonam'];
      $ngkb = $schedules[$i]['ngkb'];
      $filmekbn = $schedules[$i]['filmekbn'];

      if ($filmekbn === '0') {
        $sjtrei = $schedules[$i]['sjtrei'];
        $url = Utils::urlParamChange(array('listname'=>null, 'ymd'=>$ymd, 'kancd'=>$kancd, 'filmekbn'=>$filmekbn, 'sjtrei'=>$sjtrei));
      } else {
        $ordno = $schedules[$i]['ordno'];
        $syno = $schedules[$i]['syno'];
        $url = Utils::urlParamChange(array('listname'=>null, 'ymd'=>$ymd, 'kancd'=>$kancd, 'filmekbn'=>$filmekbn, 'ordno'=>$ordno, 'syno'=>$syno));
      }

      switch($filmekbn) {
        case '11':
          $bg_color = 'bl_categorycolor__fkb11';
          $sttime = substr($sttimeM, 1, 1);
          $sttime = $kateSttimeArray[$sttime];
          $sckb = 'ｶﾃ';
          break;
        case '0':
          $bg_color = 'bl_categorycolor__fkb0';
          $sckb = 'ope';
          break;
        case '30':
          $bg_color = 'bl_categorycolor__fkb30-32';
          $sckb = '上内';
          break;
        case '31':
          $bg_color = 'bl_categorycolor__fkb30-32';
          $sckb = '下内';
          break;
        case '32':
          $bg_color = 'bl_categorycolor__fkb30-32';
          $sckb = '気管内';
          break;
        case '33':
          $bg_color = 'bl_categorycolor__fkb33-34';
          $sckb = 'ERCP';
          break;
        case '34':
          $bg_color = 'bl_categorycolor__fkb33-34';
          $sckb = '胃婁';
          break;
        case '36':
          $bg_color = 'bl_categorycolor__fkb36';
          $sckb = '病状';
          break;
        case '14':
          $bg_color = 'bl_categorycolor__fkb14';
          $sckb = '機器';
          break;
        case '44':
          $bg_color = 'bl_categorycolor__fkb44';
          $sckb = 'ﾍﾟｰｽ';
          break;
        case '51':
          $bg_color = 'bl_categorycolor__fkb51';
          $sckb = '化学';
          break;
        default:
          $bg_color = 'bl_categorycolor__gray';
      }
      $week .= '
        <a class = "listcol bl_categorycolor__fkb11 d-flex bl_kandata justify-content-between ' . $bg_color . '" href="details.php'. $url .'">
          <dd>'. $sttime .'</dd>
          <dd>'. $sckb .'</dd>
          <dd>'. $kjnam .'</dd>
        </a>
      ';
    // }
  }

  $week .= '</div></td>';
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

?>
<section class="bl_monthlist ly_main_sec">
  <table class="bl_table bl_calendar">
    <thead>
      <tr>
        <th>日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th>土</th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach ($weeks as $week) {
          echo $week;
        }
      ?>
    </tbody>
  </table>
</section>
