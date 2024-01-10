<?php
require_once( dirname(__FILE__) . '../app/config.php');

$pdo = Database::getInstans();

$utils = new Utils();
$filmekbn = $utils->filmekbn;
$listname = $utils->listname;

$date = new Date();
$tnen = $date->tnen();
$timestamp = $date->timestamp();
$calendar = $date->createCalendar();

$schedule = new Schedule($pdo);

$kateSttimeArray = ['①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩'];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ScheduleAPP</title>
  <link rel="shortcut icon" href="img/calendar&time.png" type="image/x-icon">
  <link rel="stylesheet" href="./css/riset.css">
  <link rel="stylesheet" href="./css/style.css">
  <script src="./js/flexibility.js"></script>
</head>
<body>
  <header class="ly_header">
    <div class="ly_header_inner d-flex justify-content-between align-items-center">
      <div class="bl_systemTitle d-flex">
        <a href="index.php" class="el_logo">
          <img class="el_mainIcon_img" src="./img/calendar&time.png" alt="">
        </a>
        <a href="index.php" class="el_title">
          <h1 class="">検査手術予定表</h1>
        </a>
      </div>
      <div class="bl_header-btn">
        <div class="el_btn btn btn-light js_md-calendar-btn">
          <img src="./img/calendar_red.png" alt="">
        </div>
      </div>
  </header>
  <main>
    <div class="ly_container d-flex justify-content-between">
      <div class="row">
        <div class="ly_main <?php if( $listname == 'month'){echo 'ly_main__mr0 ly_main__w100';} ?>">
          <section class="bl_topTab d-flex justify-content-between ly_main_sec__30px">
            <a href="<?= Utils::urlParamChange(array('listname'=>'day')); ?>" class="el_tab <?php if($listname === 'day' or $listname == ''){echo 'active';} ?>">日</a>
            <a href="<?= Utils::urlParamChange(array('listname'=>'week')); ?>" class="el_tab <?php if($listname === 'week'){echo 'active';} ?>">週</a>
            <a href="<?= Utils::urlParamChange(array('listname'=>'month')); ?>" class="el_tab <?php if($listname === 'month'){echo 'active';} ?>">月</a>
          </section>
          
          <div class="bl_day d-flex justify-content-center ly_main_sec__30px">
            <a class="btn-primary js_changeDate" data-value="prevday" href="<?= Utils::urlParamChange(array('ymd'=>$date->flip('prev'))); ?>">&lt;</a>
            <?php if( $listname == "week" ): ?>
              <h2 class="el_subTitle js_datetitle"><?= date('Y年n月j日', $timestamp); ?>の週</h2>
            <?php elseif( $listname == "month"): ?>
              <h2 class="el_subTitle"><?= date('Y年n月', $timestamp); ?></h2>
            <?php else: ?>
              <h2 class="el_subTitle js_datetitle"><?= date('Y年n月j日',$timestamp); ?></h2>
            <?php endif; ?>
            <a class="btn-primary js_changeDate" data-value="nextday" href="<?= Utils::urlParamChange(array('ymd'=>$date->flip('next'))); ?>">&gt;</a>
          </div>

          <section class="bl_secTab d-flex justify-content-between ly_main_sec__30px">
            <a href="<?= Utils::urlParamChange(array('filmekbn'=>null)); ?>" class="el_tab el_tab__radiusTopLeft0 js_filmekbn_tab  <?php if (!isset($filmekbn)) { print('active');}; ?>" data-value="all">全</a>
            <a href="<?= Utils::urlParamChange(array('filmekbn'=>'11')); ?>" class="el_tab js_filmekbn_tab <?php if ($filmekbn === '11') { print('active');}; ?>" data-value="11">カテ</a>
            <a href="<?= Utils::urlParamChange(array('filmekbn'=>'0')); ?>" class="el_tab js_filmekbn_tab <?php if ($filmekbn === '0') { print('active');}; ?>" data-value="0">オペ</a>
            <a href="<?= Utils::urlParamChange(array('filmekbn'=>'44')); ?>" class="el_tab js_filmekbn_tab <?php if ($filmekbn === '44') { print('active');}; ?>" data-value="44">Pacemaker</a>
            <a href="<?= Utils::urlParamChange(array('filmekbn'=>'14')); ?>" class="el_tab js_filmekbn_tab <?php if ($filmekbn === '14') { print('active');}; ?>" data-value="14">機器管理</a>
            <a href="<?= Utils::urlParamChange(array('filmekbn'=>'3')); ?>" class="el_tab js_filmekbn_tab <?php if ($filmekbn === '3') { print('active');}; ?>" data-value="3">内視鏡</a>
            <a href="<?= Utils::urlParamChange(array('filmekbn'=>'51')); ?>" class="el_tab js_filmekbn_tab <?php if ($filmekbn === '51') { print('active');}; ?>" data-value="51">化学療法</a>
            <a href="<?= Utils::urlParamChange(array('filmekbn'=>'36')); ?>" class="el_tab el_tab__radiusTopRight0 js_filmekbn_tab <?php if ($filmekbn === '36') { print('active');}; ?>" data-value="36">病状説明</a>
          </section>

          <section>

          </section>

          <?php 
            if ($listname == 'day') {
              require_once('./daylist.php');
            } else if ($listname == 'week') {
              require_once('./weeklist.php');
            } else if ($listname == 'month') {
              require_once('./monthlist.php');
            } else {
            require_once('./daylist.php');
            }
          ?>
        </div>
        <!-- /.ly_main .col-8 -->
        
        <?php if( $listname !== 'month' or $listname === ''): ?>
          <div class="ly_sidebar bl_md-calendar js_md-calendar">
            <aside class="bl_calender">
              <div class="bl_month d-flex justify-content-center">
                <a class="btn-primary" href="<?= Utils::urlParamChange(array('ymd'=>$date->flip('calendarprev'))); ?>">&lt;</a>
                <h3 class="el_3rdTitle js_calendarShowYM"><?php echo date('Y年n月', $timestamp); ?></h3>
                <a class="btn-primary" href="<?= Utils::urlParamChange(array('ymd'=>$date->flip('calendarnext'))); ?>">&gt;</a>
              </div>
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
                  <?= $calendar; ?>
                </tbody>
              </table>
              <h3 class="el_today text-center">
                <a class="btn-outline-primary" href="<?= Utils::urlParamChange(array('ymd'=>null)); ?>">today</a>
              </h3>
            </aside>
          </div>
          <!-- /.ly_sidebar .col-4 -->
        <?php endif; ?>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.ly_container .d-flex .justify-content-between -->
  </main>
  <script>
    // var json_kateope_array = <?php echo $json_kateope_array; ?>;
    // var js_json_kateope_array = JSON.stringify(json_kateope_array);
    // var kateopes = JSON.parse(js_json_kateope_array);

    // var json_kensa_array = <?= $json_kensa_array; ?>;
    // var js_json_kensa_array = JSON.stringify(json_kensa_array);
    // var kensas = JSON.parse(js_json_kensa_array);

    var json_array = <?= $jsonArray; ?>;
    var js_json_array = JSON.stringify(json_array);
    var array = JSON.parse(js_json_array);

    
    var list_name = "<?= $list ?>";
    console.log(array);
  </script>
  <!-- <script src="./js/main.js"></script>
  <script src="./js/test.js"></script> -->
</body>
</html>