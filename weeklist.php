<?php 
  // 選択日の曜日を取得
  $youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp)));
  // 選択日の週の最初を作成
  $weekstart = date('Ymd', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp) - $youbi, date('Y', $timestamp)));
  // int型からtimestamp形式へ変換
  $weekStartTimestamp = strtotime($weekstart);
  // $weekStartTimestamp = $date->createWeekMonthStartTimestamp();
  $weeks = ["日", "月", "火", "水", "木", "金", "土"];
?>
<section class="bl_weeklist ly_main_sec">
  <table class="bl_table bl_table__borderNone bl_table__borderRadius0">
    <thead>
      <tr>
      <?php for( $i = 0; $i < 7; $i++): ?>
        <?php
          $weekDate = date('Ymd', mktime(0, 0, 0, date('m', $weekStartTimestamp), date('d', $weekStartTimestamp) + $i, date('Y', $weekStartTimestamp)));
          $viewerDate = date('n/j', mktime(0, 0, 0, date('m', $weekStartTimestamp), date('d', $weekStartTimestamp) + $i, date('Y', $weekStartTimestamp)));
        ?>
        <th>
          <a href="index.php?ymd=<?= $weekDate; ?>" style = "color:white"><?= $viewerDate . "(" . $weeks[$i] . ")"; ?></a>
        </th>
      <?php endfor; ?>
      </tr>
    </thead>
    <tbody>
      <tr>
      <?php for( $i = 0; $i < 7; $i++): ?>
        <?php
          $setday = date('Ymd', mktime(0, 0, 0, date('m', $weekStartTimestamp), date('d', $weekStartTimestamp) + $i, date('Y', $weekStartTimestamp)));
          // $schedule = new Schedule($pdo);
          $schedules = $schedule->getSchedules($tnen, $setday);
        ?>
        <td>
        <?php for ( $j = 0; $j < count($schedules); $j++): ?>
            <!-- <?php if ($setday === $schedules[$j]['ymd']): ?> -->
              <?php
                $ymd = $schedules[$j]['ymd'];
                $sttime = trim($schedules[$j]["sttime"]);
                if($sttime === '') {
                  $sttime = '';
                } else {
                  $sttimeH = substr($schedules[$j]["sttime"], 0, 2);
                  $sttimeM = substr($schedules[$j]["sttime"], 2, 2);
                  $sttime = "{$sttimeH}:{$sttimeM}";
                }
                $sckb = $schedules[$j]['sckb'];
                $kancd = $schedules[$j]['kancd'];
                $kjnam = $schedules[$j]['kjnam'];
                $sctitle = $schedules[$j]['sctitle'];
                $tantonam = $schedules[$j]['tantonam'];
                $ngkb = $schedules[$j]['ngkb'];
                $filmekbn = $schedules[$j]['filmekbn'];

                if ($filmekbn === '0') {
                  $sjtrei = $schedules[$j]['sjtrei'];
                  $url = Utils::urlParamChange(array('listname'=>null, 'ymd'=>$ymd, 'kancd'=>$kancd, 'filmekbn'=>$filmekbn, 'sjtrei'=>$sjtrei));
                } else {
                  $ordno = $schedules[$j]['ordno'];
                  $syno = $schedules[$j]['syno'];
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
              ?>
              <a class="listcol d-flex bl_kandata justify-content-between <?= $bg_color; ?>" href="details.php<?= $url; ?>">
                <dd><?= $sttime; ?></dd>
                <dd><?= $sckb; ?></dd>
                <dd><?= $kjnam; ?></dd>
              </a>
            <!-- <?php endif; ?> -->
          <?php endfor; ?>
          <!-- <?php for ( $j = 0; $j < count($schedules); $j++): ?>
            <?php if ($weekDate === $schedules[$j]['ymd']): ?>
              <?php
                $ymd = $schedules[$j]['ymd'];
                $sttime = trim($schedules[$j]["sttime"]);
                if($sttime === '') {
                  $sttime = '';
                } else {
                  $sttimeH = substr($schedules[$j]["sttime"], 0, 2);
                  $sttimeM = substr($schedules[$j]["sttime"], 2, 2);
                  $sttime = "{$sttimeH}:{$sttimeM}";
                }
                $sckb = $schedules[$j]['sckb'];
                $kancd = $schedules[$j]['kancd'];
                $kjnam = $schedules[$j]['kjnam'];
                $sctitle = $schedules[$j]['sctitle'];
                $tantonam = $schedules[$j]['tantonam'];
                $ngkb = $schedules[$j]['ngkb'];
                $filmekbn = $schedules[$j]['filmekbn'];

                if ($filmekbn === '0') {
                  $sjtrei = $schedules[$j]['sjtrei'];
                  $url = Utils::urlParamChange(array('listname'=>null, 'ymd'=>$ymd, 'kancd'=>$kancd, 'filmekbn'=>$filmekbn, 'sjtrei'=>$sjtrei));
                } else {
                  $ordno = $schedules[$j]['ordno'];
                  $syno = $schedules[$j]['syno'];
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
              ?>
              <a class="listcol d-flex bl_kandata justify-content-between <?= $bg_color; ?>" href="details.php<?= $url; ?>">
                <dd><?= $sttime; ?></dd>
                <dd><?= $sckb; ?></dd>
                <dd><?= $kjnam; ?></dd>
              </a>
            <?php endif; ?>
          <?php endfor; ?> -->
        </td>
      <?php endfor; ?>
      </tr>
    </tbody>
  </table>
</section>