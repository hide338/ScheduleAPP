<?php
require_once( dirname(__FILE__) . '../app/config.php');

$pdo = Database::getInstans();

$date = new Date();
$tnen = $date->tnen();

/**
 * URLパラメーターから各種値をGETする
 */
// 日付をymdからGET
if(isset($_GET['ymd'])){
  $ymd = Utils::h($_GET['ymd']);
} else {
  $ymd = '';
}
// 患者コードをkancdからGET
if(isset($_GET['kancd'])){
  $kancd = Utils::h($_GET['kancd']);
} else {
  $kancd = '';
}
// オーダーナンバーをordnoからGET
if(isset($_GET['ordno'])){
  $ordno = Utils::h($_GET['ordno']);
} else {
  $ordno = '';
}
// 処方番号をsynoからGET
if(isset($_GET['syno'])){
  $syno = Utils::h($_GET['syno']);
} else {
  $syno = '';
}

// フィルム分類コードをfilmkbnからGET
if(isset($_GET['filmekbn'])) {
  $filmekbn = Utils::h($_GET['filmekbn']);
} else {
  $filmekbn = '';
}

// 患者の手術投資番号をsjtreiからGET
if(isset($_GET['sjtrei'])) {
  $sjtrei = Utils::h($_GET['sjtrei']);
} else {
  $sjtrei = '';
}

/**
 * 手術詳細情報を取得
 */
$sql_single_syujutu = <<<SQL
WITH sub_tenmf AS (
  SELECT 
    tkey,
    tkjryk,
    tnen
  FROM
    tenmf
  WHERE
    tnen = ?
)

,sub_userm_dr AS (
  SELECT 
    tantocd,
    tantonam
  FROM
    userm_view
  WHERE
    yobi NOT LIKE '____1%'
    AND spflg = '1'
)

,sub_sjtdatf3 AS (
  SELECT
    sjtkancd,
    sjtrei,
    sjtymd,
    sjtsttime,
    sjtentime,
    sjtroomno,
    sjtjutusiki1,
    sjtjutusiki2,
    sjtmasui1,
    sjtmasui2,
    sjtdrcd,
    sjtjosyucd1,
    sjtmasuidr1,
    sjtmasuidr2
  FROM
    sjtdatf3
  WHERE
    sjtroomno != '98'
    AND sjtyuko = 0
    AND sjtymd = ?
    AND sjtkancd = ?
)

,sub_sjtjutusiki1 AS (
  SELECT
    sjtjutusiki1,
    sub_tenmf.tkjryk AS jutusiki1
  FROM
    sub_sjtdatf3 datf3
      LEFT JOIN sub_tenmf
        ON datf3.sjtjutusiki1 = sub_tenmf.tkey
  WHERE
    datf3.sjtjutusiki1 != ''
)

,sub_sjtjutusiki2 AS (
  SELECT
    sjtjutusiki2,
    sub_tenmf.tkjryk AS jutusiki2
  FROM
    sub_sjtdatf3 datf3
      LEFT JOIN sub_tenmf
        ON datf3.sjtjutusiki2 = sub_tenmf.tkey
  WHERE
    datf3.sjtjutusiki2 != ''
)

,sub_sjtmasui1 AS (
  SELECT
    sjtmasui1,
    sub_tenmf.tkjryk AS masui1
  FROM
    sub_sjtdatf3 datf3
      LEFT JOIN sub_tenmf
        ON datf3.sjtmasui1 = sub_tenmf.tkey
  WHERE
    datf3.sjtmasui1 != ''
)

,sub_sjtmasui2 AS (
  SELECT
    sjtmasui2,
    sub_tenmf.tkjryk AS masui2
  FROM
    sub_sjtdatf3 datf3
      LEFT JOIN sub_tenmf
        ON datf3.sjtmasui2 = sub_tenmf.tkey
  WHERE
    datf3.sjtmasui2 != ''
)

,sub_sjtdr AS (
  SELECT
    sjtdrcd,
    sub_userm_dr.tantonam AS dr
  FROM
    sub_sjtdatf3 datf3
      LEFT JOIN sub_userm_dr
        ON datf3.sjtdrcd = sub_userm_dr.tantocd
  WHERE
    datf3.sjtdrcd != ''
)

,sub_sjtjosyudr AS (
  SELECT
    sjtjosyucd1,
    sub_userm_dr.tantonam AS josyudr
  FROM
    sub_sjtdatf3 datf3
      LEFT JOIN sub_userm_dr
        ON datf3.sjtjosyucd1 = sub_userm_dr.tantocd
  WHERE
    datf3.sjtjosyucd1 != ''
)

,sub_sjtmasuidr1 AS (
  SELECT
    sjtmasuidr1,
    sub_userm_dr.tantonam AS masuidr1
  FROM
    sub_sjtdatf3 datf3
      LEFT JOIN sub_userm_dr
        ON datf3.sjtmasuidr1 = sub_userm_dr.tantocd
  WHERE
    datf3.sjtmasuidr1 != ''
)

,sub_sjtmasuidr2 AS (
  SELECT
    sjtmasuidr2,
    sub_userm_dr.tantonam AS masuidr2
  FROM
    sub_sjtdatf3 datf3
      LEFT JOIN sub_userm_dr
        ON datf3.sjtmasuidr2 = sub_userm_dr.tantocd
  WHERE
    datf3.sjtmasuidr2 != ''
)

SELECT
  sjtkancd,
  sjtrei,
  sjtymd,
  sjtsttime,
  sjtentime,
  CASE
    WHEN sjtroomno = '01'
      THEN '手術室1'
    WHEN sjtroomno = '02'
      THEN '手術室2'
    ELSE 'カテ室'
  END AS sjtroom,
  CASE
    WHEN jutusiki1 IS NOT NULL
      THEN jutusiki1
    ELSE ''
  END AS jutusiki1,
  CASE
    WHEN jutusiki2 IS NOT NULL
      THEN jutusiki2
    ELSE ''
  END AS jutusiki2,
  CASE
    WHEN masui1 IS NOT NULL
      THEN masui1
    ELSE ''
  END AS masui1,
  CASE
    WHEN masui2 IS NOT NULL
      THEN masui2
    ELSE ''
  END AS masui2,
  CASE
    WHEN dr IS NOT NULL
      THEN dr
    ELSE ''
  END AS dr,
  CASE
    WHEN josyudr IS NOT NULL
      THEN josyudr
    ELSE ''
  END AS josyudr,
  CASE
    WHEN masuidr1 IS NOT NULL
      THEN masuidr1
    ELSE ''
  END AS masuidr1,
  CASE
    WHEN masuidr2 IS NOT NULL
      THEN masuidr2
    ELSE ''
  END AS masuidr2
FROM
  sub_sjtdatf3 datf3
    LEFT JOIN sub_sjtjutusiki1
      ON datf3.sjtjutusiki1 = sub_sjtjutusiki1.sjtjutusiki1
    LEFT JOIN sub_sjtjutusiki2
      ON datf3.sjtjutusiki2 = sub_sjtjutusiki2.sjtjutusiki2
    LEFT JOIN sub_sjtmasui1
      ON datf3.sjtmasui1 = sub_sjtmasui1.sjtmasui1
    LEFT JOIN sub_sjtmasui2
      ON datf3.sjtmasui2 = sub_sjtmasui2.sjtmasui2
    LEFT JOIN sub_sjtdr
      ON datf3.sjtdrcd = sub_sjtdr.sjtdrcd
    LEFT JOIN sub_sjtjosyudr
      ON datf3.sjtdrcd = sub_sjtjosyudr.sjtjosyucd1
    LEFT JOIN sub_sjtmasuidr1
      ON datf3.sjtmasuidr1 = sub_sjtmasuidr1.sjtmasuidr1
    LEFT JOIN sub_sjtmasuidr2
      ON datf3.sjtmasuidr2 = sub_sjtmasuidr2.sjtmasuidr2
SQL;

$single_syujutu = $pdo->prepare($sql_single_syujutu);
// bindPramでそれぞれ取得してきた変数を代入
$single_syujutu->bindParam(1,$tnen,PDO::PARAM_INT);
$single_syujutu->bindParam(2,$ymd,PDO::PARAM_INT);
$single_syujutu->bindParam(3,$kancd,PDO::PARAM_INT);
// SQL結果を取得
$single_syujutu->execute();
$single_syujutu = $single_syujutu->fetchAll();

/**
 * 術式を手入力しているものを取得
 */
$sql_sjtjutucoms_text = <<<SQL
  SELECT 
    sjtkakujutucom
  FROM
    sjtdatf3sub2
  WHERE
    sjtkakujutucom != ''
    AND sjtkancd =?
    AND sjtrei = ?
SQL;
$sjtjutucoms_text = $pdo->prepare($sql_sjtjutucoms_text);
// bindPramでそれぞれ取得してきた変数を代入
$sjtjutucoms_text->bindParam(1,$kancd,PDO::PARAM_INT);
$sjtjutucoms_text->bindParam(2,$sjtrei,PDO::PARAM_INT);
// SQL結果を取得
$sjtjutucoms_text->execute();
$sjtjutucoms_text = $sjtjutucoms_text->fetchAll();

/**
 * 手術病名を取得
 */
// sjtbyokanテーブルから取得
$sql_sjtbyomeis = <<<SQL
SELECT
  sjtbyomei
FROM
  sjtbyokan
WHERE
  sjtkancd = ?
  ANd sjtrei = ?
  AND sjtjissikbn = 0
SQL;
$sjtbyomeis = $pdo->prepare($sql_sjtbyomeis);
// bindPramでそれぞれ取得してきた変数を代入
$sjtbyomeis->bindParam(1,$kancd,PDO::PARAM_INT);
$sjtbyomeis->bindParam(2,$sjtrei,PDO::PARAM_INT);
// SQL結果を取得
$sjtbyomeis->execute();
$sjtbyomeis = $sjtbyomeis->fetchAll();

//sjtdatf3sub3テーブルから手入力の病名取得
$sql_sjtbyomeis_text = <<<SQL
SELECT
  sjttext AS sjtbyomei
FROM
  sjtdatf3sub3
WHERE
  sjtbunrui = '04'
  AND sjtkancd = ?
  AND sjtrei = ?
SQL;
$sjtbyomeis_text = $pdo->prepare($sql_sjtbyomeis_text);
// bindPramでそれぞれ取得してきた変数を代入
$sjtbyomeis_text->bindParam(1,$kancd,PDO::PARAM_INT);
$sjtbyomeis_text->bindParam(2,$sjtrei,PDO::PARAM_INT);
// SQL結果を取得
$sjtbyomeis_text->execute();
$sjtbyomeis_text = $sjtbyomeis_text->fetchAll();

/**
 * 手術依頼コメントの取得
 */
$sql_iraicomments = <<<SQL
SELECT
  sjttext
FROM
  sjtdatf3sub3
WHERE
  sjtbunrui = '29'
  AND sjtkancd = ?
  AND sjtrei = ?
SQL;
$iraicomments = $pdo->prepare($sql_iraicomments);
// bindPramでそれぞれ取得してきた変数を代入
$iraicomments->bindParam(1,$kancd,PDO::PARAM_INT);
$iraicomments->bindParam(2,$sjtrei,PDO::PARAM_INT);
// SQL結果を取得
$iraicomments->execute();
$iraicomments = $iraicomments->fetchAll();

/**
 * 手術フリーコメントの取得
 */
$sql_freecomments = <<<SQL
SELECT
  sjttext
FROM
  sjtdatf3sub3
WHERE
  sjtbunrui = '83'
  AND sjtkancd = ?
  AND sjtrei = ?
SQL;
$freecomments = $pdo->prepare($sql_freecomments);
// bindPramでそれぞれ取得してきた変数を代入
$freecomments->bindParam(1,$kancd,PDO::PARAM_INT);
$freecomments->bindParam(2,$sjtrei,PDO::PARAM_INT);
// SQL結果を取得
$freecomments->execute();
$freecomments = $freecomments->fetchAll();

/**
 * 個別の詳しい検査情報を取得
 */
$sql_single_kensa = <<<SQL
WITH sub_tenmf AS(
  SELECT
    tkey,
    tkjryk,
    tshinno,
    SUBSTRING(tensanteikbn2,6,2) AS filmekbn,
    SUBSTRING(tensanteikbn2, 17, 2) AS yoteikbn
  FROM
    tenmf
      LEFT JOIN tensubm sub
        ON tenmf.tkey = sub.tenkey
  WHERE
    tnen = ?
)

--【検査予定情報サブクエリ】予約時間を抽出する為、orddatfの自己リレーション用のクエリ
,sub_self_orddatf AS (
  SELECT 
    kancd,
    ordno,
    ymd,
    syno,
    SUBSTRING(tokdt, 17, 4) AS yoyakutime
  FROM 
    orddatf 
  WHERE
    bunrui2 = 3
    AND syinno = 999
    AND ymd = ?
)

--【検査予定情報サブクエリ】患者名を補完する為のクエリ
,sub_kantable AS (
  SELECT 
    code,
    knsei,
    knmei,
    kjnam
  FROM
    kanmf
  WHERE
    code >= '0030000'
    AND code <= '0090000'
    AND kjnam NOT LIKE '【使用禁止】%'
)

--【検査予定情報サブクエリ】依頼医師名を補完する為のクエリ
,sub_userm AS (
  SELECT 
    tantocd,
    tantonam
  FROM
    userm_view
  WHERE
    yobi NOT LIKE '____1%'
    AND spflg = '1'
)

--【検査予定情報サブクエリ】フィルム分類の名称を補完する為のクエリ
,sub_namef AS (
  SELECT 
    RIGHT(namkey, 2) AS namkey,
    kjnam
  FROM
    namef
  WHERE
    namkey LIKE '69%'
)

SELECT
  DISTINCT
  orddatf.ymd,
  orddatf.kancd,
  sub_self_orddatf.yoyakutime,
  sub_kantable.kjnam,
  sub_tenmf.tkjryk,
  sub_userm.tantonam,
  *
FROM
  orddatf
    LEFT JOIN sub_tenmf
    ON orddatf.tencd = sub_tenmf.tkey
    LEFT JOIN sub_self_orddatf
      ON orddatf.kancd = sub_self_orddatf.kancd
      AND orddatf.ymd = sub_self_orddatf.ymd
      AND orddatf.ordno = sub_self_orddatf.ordno
      AND orddatf.syno = sub_self_orddatf.syno
    LEFT JOIN sub_kantable
      ON orddatf.kancd = sub_kantable.code
    LEFT JOIN sub_userm
      ON orddatf.drcd = sub_userm.tantocd
    LEFT JOIN sub_namef
      ON sub_tenmf.filmekbn = sub_namef.namkey
WHERE
  orddatf.ymd = ?
  AND orddatf.kancd = ?
  AND orddatf.ordno = ?
  AND orddatf.syno = ?
  AND orddatf.bunrui2 = '3'
  AND sub_tenmf.tkey != 'DELETE'
ORDER BY
  orddatf.syinno ASC
SQL;

$single_kensa = $pdo->prepare($sql_single_kensa);
// bindPramでそれぞれ取得してきた変数を代入
$single_kensa->bindParam(1,$tnen,PDO::PARAM_INT);
$single_kensa->bindParam(2,$ymd,PDO::PARAM_INT);
$single_kensa->bindParam(3,$ymd,PDO::PARAM_INT);
$single_kensa->bindParam(4,$kancd,PDO::PARAM_INT);
$single_kensa->bindParam(5,$ordno,PDO::PARAM_INT);
$single_kensa->bindParam(6,$syno,PDO::PARAM_INT);
// SQL結果を取得
$single_kensa->execute();
$single_kensa = $single_kensa->fetchAll();

/**
 * 患者情報を取得
 */
$sql_kandata = <<<SQL
  SELECT
    kanmf.code,
    kanmf.knsei,
    kanmf.knmei,
    kanmf.kjnam,
    CASE
      WHEN kanmf.sex = 1
        THEN '男'
      WHEN kanmf.sex = 3
        THEN '女'
      ELSE '不明'
    END AS sex,
    kanmf.birth,
    kansubf.height,
    kansubf.weight,
    CASE
      WHEN SUBSTRING(kansubf.sonota1,5,1) = 0
      THEN '未'
    WHEN SUBSTRING(kansubf.sonota1,5,1) = 1
      THEN 'A'
    WHEN SUBSTRING(kansubf.sonota1,5,1) = 2
      THEN 'B'
      WHEN SUBSTRING(kansubf.sonota1,5,1) = 3
      THEN 'O'
    WHEN SUBSTRING(kansubf.sonota1,5,1) = 4
      THEN 'AB'
    ELSE '未'
    END AS blood,
    CASE
      WHEN SUBSTRING(kansubf.sonota1,6,1) = 0
      THEN '未'
    WHEN SUBSTRING(kansubf.sonota1,6,1) = 1
      THEN 'Rh+'
    WHEN SUBSTRING(kansubf.sonota1,6,1) = 2
      THEN 'Rh-'
    ELSE '未'
    END AS bloodsub
  FROM
    kanmf
      LEFT JOIN kansubf
      ON kanmf.code = kansubf.code
  WHERE
    kanmf.code = ?
SQL;

$kandata = $pdo->prepare($sql_kandata);
// bindPramでそれぞれ取得してきた変数を代入
$kandata->bindParam(1,$kancd,PDO::PARAM_INT);
// SQL結果を取得
$kandata->execute();
$kandata = $kandata->fetchAll();

// var_dump($kandata);

/**
 * 患者の禁忌情報を取得
 */
$sql_kinkis = <<<SQL
  SELECT
    kankinkihd.kancd,
    kankinkihd.code2,
    namef.kjnam,
    kankinkihd.stymd,
    kankinkihd.enymd,
    kankinkihd.kbn,
    kankinkihd.seq
  FROM
    kankinkihd
      LEFT JOIN namef
        ON kankinkihd.code2 = namef.namkey
  WHERE
    kankinkihd.code = '000000'
    AND kankinkihd.ykkb = 0
    AND kankinkihd.kancd = ?
    AND kankinkihd.stymd <= ?
    AND kankinkihd.enymd >= ?
  ORDER BY
    kankinkihd.kancd, kankinkihd.kbn, kankinkihd.stymd, kankinkihd.seq ASC
SQL;

$kinkis = $pdo->prepare($sql_kinkis);
// bindPramでそれぞれ取得してきた変数を代入
$kinkis->bindParam(1,$kancd,PDO::PARAM_INT);
$kinkis->bindParam(2,$ymd,PDO::PARAM_INT);
$kinkis->bindParam(3,$ymd,PDO::PARAM_INT);
// SQL結果を取得
$kinkis->execute();
$kinkis = $kinkis->fetchAll();

// var_dump($kinkis);

/**
 * 患者の禁忌情報でコメント部分を保管するSQL
 */
$sql_kinkisub = <<<SQL
  SELECT
    kancd,
    data,
    kbn,
    seq,
    seq2
  FROM
    kankinkidat
  WHERE
    kancd = ?
    AND code = '000000'
  ORDER BY
    kbn, seq, seq2 ASC
SQL;

$kinkisub = $pdo->prepare($sql_kinkisub);
// bindPramでそれぞれ取得してきた変数を代入
$kinkisub->bindParam(1,$kancd,PDO::PARAM_INT);
// SQL結果を取得
$kinkisub->execute();
$kinkisub = $kinkisub->fetchAll();

// var_dump($kinkis);

/**
 * 患者の感染症情報を取得
 */
$sql_kansens = <<<SQL
SELECT
  kankinki.kancd,
  kankinki.code,
  kankinki.kjnam,
  kankinki.data,
  kankinki.stymd,
  kankinki.enymd,
  kankinki.kbn,
  kankinki.seq
FROM
  (SELECT
    kankinkihd.kancd,
    kankinkihd.code,
    namef.kjnam,
    kankinkidat.data,
    kankinkihd.stymd,
    kankinkihd.enymd,
    kankinkihd.kbn,
    kankinkihd.seq,
    ROW_NUMBER() OVER(PARTITION BY kankinkihd.code ORDER BY kankinkihd.stymd DESC) num
  FROM
    kankinkihd
      LEFT JOIN namef
        ON kankinkihd.code = namef.namkey
      LEFT JOIN kankinkidat
        ON kankinkihd.kancd = kankinkidat.kancd
        AND kankinkihd.code =kankinkidat.code
  WHERE
    code2 = '000000'
    AND kankinkihd.ykkb = 0
    AND kankinkihd.kancd = ?
) kankinki
WHERE
  kankinki.num = 1
SQL;

$kansens = $pdo->prepare($sql_kansens);
// bindPramでそれぞれ取得してきた変数を代入
$kansens->bindParam(1,$kancd,PDO::PARAM_INT);
// SQL結果を取得
$kansens->execute();
$kansens = $kansens->fetchAll();

$kinki_kbns = [['kbn' => '01', 'abbname' => '(薬)'], ['kbn' => '06', 'abbname' => '(食)'], ['kbn' => '07', 'abbname' => '(食)'], ['kbn' => '12', 'abbname' => '(他)'], ['kbn' => '05', 'abbname' => '(抗)'], ['kbn' => '04', 'abbname' => '(輸)'], ['kbn' => '11', 'abbname' => '(検)'], ['kbn' => '13', 'abbname' => '(身)']];
$kansen_kbns = [['kbn' => '81', 'abbname' => '(感)']];

// var_dump($kinki_kbns);

$host = $_SERVER['HTTP_HOST'];

/**
 * 前の画面に戻るためのURLを変数にセット、「戻るボタン」へ埋め込み
 */
if (!empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], $host)) !== false) {
  $http_return = $_SERVER['HTTP_REFERER'];
} else {
  $http_return = "http://".$host."/schedule/index.php";
}

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
    <div class="ly_header_inner">
      <div class="bl_systemTitle d-flex">
        <a href="index.php" class="el_logo">
          <img class="el_mainIcon_img" src="./img/calendar&time.png" alt="">
        </a>
        <a href="index.php" class="el_title">
          <h1 class="">検査手術予定表</h1>
        </a>
      </div>
    </div>
  </header>
  <main>
    <div class="bl_card bl_kaninfo">
      <div class="bl_cardBody d-flex row">
        <!-- <h2 class="card-title">患者情報</h2> -->
        <section class="bl_abb-data col-5">
          <div class="d-flex">
            <div class="kandata-left">
              <h3 class="kancode"><?= $kandata[0]['code']; ?></h3>
            <?php if($kandata[0]['sex'] === "男"): ?>
              <div class="kandata_sex"><img src="./img/man.png" alt=""></div>
            <?php else: ?>
              <div class="kandata_sex"><img src="./img/woman.png" alt=""></div>
            <?php endif; ?>
            </div>
            <div class="kandata_right">
              <div class="d-flex kandata_right_name-blood">
                <div class="kandata_right_name">
                  <div class="d-flex kandata_right_name-kana">
                    <p><?= $kandata[0]['knsei']; ?></p>
                    <p><?= $kandata[0]['knmei']; ?></p>
                  </div>
                  <h3 class="kandata_right_name-kanzi"><?= $kandata[0]['kjnam']; ?><span>様</span></h3>
                </div>
                <div class="kandata_right_bloodtype">
                  <?php 
                    $blood = $kandata[0]['blood'];
                    $rh = $kandata[0]['bloodsub'];
                  ?>
                  <?php if ($blood === 'A' && $rh === 'Rh+'): ?>
                    <img src="./img/a+.png" alt="">
                  <?php elseif ($blood === 'A' && $rh === 'Rh-'): ?>
                    <img src="./img/a-.png" alt="">
                  <?php elseif ($blood === 'B' && $rh === 'Rh+'): ?>
                    <img src="./img/b+.png" alt="">
                  <?php elseif ($blood === 'B' && $rh === 'Rh-'): ?>
                    <img src="./img/b-.png" alt="">
                  <?php elseif ($blood === 'O' && $rh === 'Rh+'): ?>
                    <img src="./img/o+.png" alt="">
                  <?php elseif ($blood === 'O' && $rh === 'Rh-'): ?>
                    <img src="./img/o-.png" alt="">
                  <?php elseif ($blood === 'AB' && $rh === 'Rh+'): ?>
                    <img src="./img/ab+.png" alt="">
                  <?php elseif ($blood === 'AB' && $rh === 'Rh-'): ?>
                    <img src="./img/ab-.png" alt="">
                  <?php else: ?>
                    <img src="./img/blood-type_10008951.png" alt="">
                  <?php endif; ?>
                </div>
              </div>
              <div class="d-flex kandata_right_sub-info">
                <p><?= substr($kandata[0]['birth'], 0, 4); ?><span>年</span><?= substr($kandata[0]['birth'], 4, 2); ?><span>月</span><?= substr($kandata[0]['birth'], 6, 2); ?><span>日生</span></p>
              <?php if( substr($kandata[0]['birth'], 4, 4) <= substr($ymd, 4, 4)): ?>
                <p><?= substr($ymd, 0, 4) - substr($kandata[0]['birth'], 0, 4) ?><span>歳</span></p>
              <?php else: ?>
                <p><?= substr($ymd, 0, 4) - substr($kandata[0]['birth'], 0, 4) - 1 ?><span>歳</span></p>
              <?php endif; ?>
                <p><?= $kandata[0]['height']; ?><span>cm</span></p>
                <p><?= $kandata[0]['weight']; ?><span>kg</span></p>
              </div>
            </div>
          </div>
        </section>
        <section class="bl_kinki-kansen-info col-7">
          <a class="bl_kinki-kansen-info_tabtitle js_kinki-tab tab-active">禁忌</a>
          <a class="bl_kinki-kansen-info_tabtitle js_kansen-tab">感染症</a>
          <section class="bl_kinki js_kinki-table">
            <?php if(count($kinkis) === 0): ?>
              <p class="el_nodata">禁忌登録なし</p>
            <?php else: ?>
            <table class="">
              <thead>
                <tr>
                  <th>日付</th>
                  <th>区分</th>
                  <th>項目</th>
                  <th>内容</th>
                </tr>
              </thead>
              <tbody>
              <?php for ($i=0; $i < count($kinkis); $i++): ?>
                <tr>
                  <td><?= $kinkis[$i]['stymd']; ?></td>
                  <td>
                    <?php for($j=0; $j < count($kinki_kbns); $j++): ?>
                      <?php if ($kinkis[$i]['kbn'] === $kinki_kbns[$j]['kbn']): ?>
                        <?= $kinki_kbns[$j]['abbname'] ?>
                      <?php endif; ?>
                    <?php endfor; ?>
                  </td>
                  <td><?= $kinkis[$i]['kjnam']; ?></td>
                  <td>
                    <?php for ($k=0; $k < count($kinkisub); $k++): ?>
                      <?php if ($kinkis[$i]['kbn'] === $kinkisub[$k]['kbn'] && $kinkis[$i]['seq'] === $kinkisub[$k]['seq']): ?>
                        <p><?= $kinkisub[$k]['data'] ?></p>
                      <?php endif; ?>
                    <?php endfor; ?>
                  </td>
                </tr>
              <?php endfor; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </section>
          <section class="bl_kansen js_kansen-table hidden">
            <?php if(count($kansens) === 0): ?>
              <p class="el_nodata">感染症データなし</p>
            <?php else: ?>
            <table class="">
              <thead>
                <tr>
                  <th>日付</th>
                  <th>区分</th>
                  <th>項目</th>
                  <th>内容</th>
                </tr>
              </thead>
              <tbody>
              <?php for ($i=0; $i < count($kansens); $i++): ?>
                <tr>
                  <td><?= $kansens[$i]['stymd']; ?></td>
                  <td>
                    <?php for($j=0; $j < count($kansen_kbns); $j++): ?>
                      <?php if($kansens[$i]['kbn'] === $kansen_kbns[$j]['kbn']): ?>
                        <?= $kansen_kbns[$j]['abbname'] ?>
                      <?php endif; ?>
                    <?php endfor; ?>
                  </td>
                  <td><?= $kansens[$i]['kjnam']; ?></td>
                  <td><?= $kansens[$i]['data']; ?></td>
                </tr>
              <?php endfor; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </section>
        </section>
      </div>
    </div>
    <div class="bl_card">
      <div class="bl_cardBody bl_order">
        <h2 class="el_order__title">オーダー内容</h2>
          <dl class="bl_order-list">
          <?php if($filmekbn === '0'): ?>
            <dt class="el_order-list__title">病名</dt>
            <?php if (count($sjtbyomeis) > 0): ?>
              <dd class="el_order-list__data">
                <?php 
                  for ($i = 0; $i < count($sjtbyomeis); $i++) {
                    echo $sjtbyomeis[$i]['sjtbyomei'];
                  }
                ?>
              </dd>
            <?php endif; ?>
            <?php if (count($sjtbyomeis_text) > 0): ?>
              <dd class="el_order-list__data">
                <?php 
                  for ($i = 0; $i < count($sjtbyomeis_text); $i++) {
                    echo $sjtbyomeis_text[$i]['sjtbyomei'];
                  }
                ?>
              </dd>
            <?php endif; ?>
            <?php for ($i = 0; $i < count($single_syujutu); $i++): ?>
              <dt class="el_order-list__title">術式</dt>
              <dd class="el_order-list__data"><?= $single_syujutu[$i]['jutusiki1']; ?></dd>
              <dd class="el_order-list__data"><?= $single_syujutu[$i]['jutusiki2']; ?></dd>
              <?php if (count($sjtjutucoms_text) > 0): ?>
                <?php for ($j = 0; $j < count($sjtjutucoms_text); $j++): ?>
                  <dd class="el_order-list__data"><?= $sjtjutucoms_text[$i]['sjtkakujutucom']; ?></dd>
                <?php endfor; ?>
              <?php endif; ?>
              <dt class="el_order-list__title">執刀医</dt>
              <dd class="el_order-list__data"><?= $single_syujutu[$i]['dr']; ?></dd>
              <?php if (empty($single_syujutu[$i]['josyudr'])): ?>
                <dt class="el_order-list__title">助手</dt>
                <dd class="el_order-list__data"><?= $single_syujutu[$i]['josyudr']; ?></dd>
              <?php endif; ?>
              <dt class="el_order-list__title">麻酔法</dt>
              <dd class="el_order-list__data"><?= $single_syujutu[$i]['masui1']; ?></dd>
              <dd class="el_order-list__data"><?= $single_syujutu[$i]['masui2']; ?></dd>
              <dt class="el_order-list__title">麻酔医</dt>
              <dd class="el_order-list__data"><?= $single_syujutu[$i]['masuidr1']; ?></dd>
              <dd class="el_order-list__data"><?= $single_syujutu[$i]['masuidr2']; ?></dd>
            <?php endfor; ?>
            <?php if (count($iraicomments) > 0): ?>
              <dt class="el_order-list__title">依頼コメント</dt>
              <dd class="el_order-list__data">
                <?php
                  for ($i = 0; $i < count($iraicomments); $i++) {
                    echo $iraicomments[$i]['sjttext'];
                  }
                ?>
              </dd>
            <?php endif; ?>
            <?php if (count($freecomments) > 0): ?>
              <dt class="el_order-list__title">フリーコメント</dt>
              <dd class="el_order-list__data">
                <?php
                    for ($i = 0; $i < count($freecomments); $i++) {
                      echo $freecomments[$i]['sjttext'];
                    }
                ?>
              </dd>
            <?php endif; ?>
          <?php else: ?>
            <?php for ($i=0; $i < count($single_kensa); $i++): ?>
              <dd class="el_order-list__data"><?= $single_kensa[$i]['tkjryk']; ?></dd>
            <?php endfor; ?>
          <?php endif; ?>
          </dl>
      </div>
    </div>
    <a href="<?= $http_return; ?>" class="btn btn-primary el_btn__return">前に戻る</a>
    <!-- <div class="el_btn el_btn__back">
    </div> -->
  </main>
  <script src="./js/details.js"></script>
</body>
</html>