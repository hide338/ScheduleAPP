<?php
  $setday = date('Ymd', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp)));
  // $schedule = new Schedule($pdo);
  $schedules = $schedule->getSchedules($tnen, $setday);
?>
<section class="bl_daylist ly_main_sec">
  <table class="bl_table bl_table__borderNone bl_table__borderRadius0">
    <thead>
      <tr class="">
        <th></th>
        <th>開始時間</th>
        <th>区分</th>
        <th>患者ID</th>
        <th>患者名</th>
        <th>検査詳細</th>
        <th>依頼医</th>
        <th>施行医</th>
        <th>入外</th>
      </tr>
    </thead>
    <tbody>
      <?php for ($i=0; $i < count($schedules) ; $i++): ?>
        <?php
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
              break;
            case '0':
              $bg_color = 'bl_categorycolor__fkb0';
              break;
            case '30':
            case '31':
            case '32':
              $bg_color = 'bl_categorycolor__fkb30-32';
              break;
            case '33':
            case '34':
              $bg_color = 'bl_categorycolor__fkb33-34';
              break;
            case '36':
              $bg_color = 'bl_categorycolor__fkb36';
              break;
            case '14':
              $bg_color = 'bl_categorycolor__fkb14';
              break;
            case '44':
              $bg_color = 'bl_categorycolor__fkb44';
              break;
            case '51':
              $bg_color = 'bl_categorycolor__fkb51';
              break;
            default:
              $bg_color = 'bl_categorycolor__gray';
          }
        ?>
        <tr class="<?= $bg_color; ?>">
          <td><a href="details.php<?= $url; ?>"><img class="el_icon" src="./img/folder.png" alt=""></a></td>
          <td><?= $sttime; ?></td>
          <td><?= $sckb; ?></td>
          <td><?= $kancd; ?></td>
          <td><?= $kjnam ?></td>
          <td><?= $sctitle; ?></td>
          <?php if ($filmekbn === "0"): ?>
            <td></td>
            <td><?= $tantonam; ?></td>
          <?php else: ?>
            <td><?= $tantonam; ?></td>
            <td></td>
          <?php endif; ?>
          <td><?= $ngkb; ?></td>
        </tr>
      <?php endfor; ?>
    </tbody>
  </table>
</section>