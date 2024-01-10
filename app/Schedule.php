<?php

class Schedule
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function getSchedules($tnen, $setday) {
    $filmekbn = filter_input(INPUT_GET, 'filmekbn');

    $ope = $this->getOpe($tnen, $setday);
    $kensa = $this->getKensa($tnen, $setday);

    $array = [];
    
    if (!isset($filmekbn)) {
      $array = array_merge($ope, $kensa);
    } elseif ($filmekbn === '0') {
      $array = $ope;
    } else {
      for ($i = 0; $i < count($kensa); $i++) {
        if ($filmekbn === $kensa[$i]['filmekbn'] ) {
          array_push($array, $kensa[$i]);
        } elseif ($filmekbn === substr($kensa[$i]['filmekbn'], 0, 1) && $kensa[$i]['filmekbn'] !== '36') {
          array_push($array, $kensa[$i]);
        }
      }
    }

    return $array;
  }


  private function getOpe($tnen, $setday) {
    /**
     * カテオペの予定を抽出するSQL
     */
    $ope =<<<SQL
      /* 【カテオペ情報サブクエリ】sjtdatf3から手術データを抽出し、tenmfとuserm_viewをリレーションしてデータを補完する */
      WITH subq_tenmf AS (
        SELECT 
          tkey,
          tkjryk,
          tnen
        FROM
          tenmf
        WHERE
          tnen = ?
      )

      ,subq_kanmf AS (
        SELECT 
          code,
          kjnam
        FROM
          kanmf
        WHERE
          code >= '0030000'
          AND code <= '0090000'
          AND kjnam NOT LIKE '【使用禁止】%'
      )

      ,subq_userm_dr AS (
        SELECT 
          tantocd,
          tantonam
        FROM
          userm_view
        WHERE
          yobi NOT LIKE '____1%'
          AND spflg = '1'
      )

      ,subq_sjtdatf AS (
        SELECT 
          sjtkancd,
          subq_kanmf.kjnam,
          sjtrei,
          sjtymd,
          sjtsttime,
          sjtentime,
          sjtroomno,
          sjtngkb,
          sjtsnk,
          sjtkekkakbn,
          sjtjutusiki1,
          sjtdrcd,
          subq_userm_dr.tantonam,
          /* sjtdatf3のsjtjutusiki1は直接入力の術式があった場合は空白になり、そのリレーション先であるtenmfはnullになってしまう為、nullをunknに置き換え、別のSELECTで抽出しやすくする */
          ISNULL(subq_tenmf.tnen, 'unkn') tnen,
          CASE 
            WHEN tkjryk IS NULL
              THEN '' 
              ELSE tkjryk
          END tkjryk
        FROM 
          sjtdatf3
            /* 術式を表示させる為のリレーション */
            LEFT JOIN subq_tenmf
              ON sjtdatf3.sjtjutusiki1 = subq_tenmf.tkey
            /* 患者名を表示させる為のリレーション */
            LEFT JOIN subq_kanmf 
              ON sjtdatf3.sjtkancd = subq_kanmf.code
            /* 執刀医を表示する為のリレーション */
            LEFT JOIN subq_userm_dr 
              ON sjtdatf3.sjtdrcd = subq_userm_dr.tantocd
            /* 手術予定日の絞り込みと、オーダーの有効分のみ表示させる設定 */
        WHERE 
          sjtdatf3.sjtyuko = 0
          AND sjtdatf3.sjtroomno != '98'
          AND sjtdatf3.sjtkancd >= '0030000'
          AND sjtdatf3.sjtkancd <= '0090000'
          AND sjtdatf3.sjtymd = ?
      )

      -- 【カテオペ情報サブクエリ】術式を手入力している患者もいる為、sjtdatf3sub2から手入力されているものを抽出するクエリ
      , subq_sjtdatfsub AS (
        SELECT 
          sjtkancd,
          sjtrei,
          sjtkakujutucom
        FROM
          sjtdatf3sub2
        WHERE
          sjtkakujutucom != ''
          AND sjtkancd >= '0030000'
          AND sjtkancd <= '0090000'
      )

      -- 【カテオペ情報サブクエリ】依頼医を抽出するクエリ
      , subq_sjtdatf3sub3 AS (
        SELECT
          sjtkancd,
          sjtrei,
          tantonam
        FROM
          sjtdatf3sub3
            LEFT JOIN subq_userm_dr
              ON sjtdatf3sub3.sjttext = subq_userm_dr.tantocd
        WHERE
          sjtdatf3sub3.sjtbunrui = 'A2'
          AND sjtkancd >= '0030000'
          AND sjtkancd <= '0090000'
      )

      -- 【カテオペ情報メインクエリ】カテオペ予定抽出のサブクエリをまとめて、カテオペ情報メインクエリとする
      , m_kateope AS (
        SELECT
          subq_sjtdatf.sjtymd,
          subq_sjtdatf.sjtsttime,
          subq_sjtdatf.sjtkancd,
          subq_sjtdatf.kjnam,
          subq_sjtdatf.sjtrei,
          CASE
            WHEN subq_sjtdatf.sjtroomno = 98
              THEN 'カテ･アンギオ'
            ELSE 'オペ'
          END AS 'sjtkb',
          CASE
            WHEN subq_sjtdatf.sjtroomno = 01
              THEN '手術室1'
            WHEN subq_sjtdatf.sjtroomno = 02
              THEN '手術室2'
            ELSE 'カテ室'
          END AS 'sjtroom',
          subq_sjtdatf.tnen,
          CASE
            WHEN subq_sjtdatf.tkjryk = ''
            AND subq_sjtdatfsub.sjtkakujutucom IS NOT NULL
              THEN subq_sjtdatfsub.sjtkakujutucom
            WHEN subq_sjtdatf.tkjryk != ''
            AND subq_sjtdatfsub.sjtkakujutucom IS NOT NULL
              THEN RTRIM(subq_sjtdatf.tkjryk) + '/' + subq_sjtdatfsub.sjtkakujutucom
            ELSE subq_sjtdatf.tkjryk
          END AS 'tkjryk',
          subq_sjtdatf3sub3.tantonam AS sijidr,
          subq_sjtdatf.tantonam,
          subq_sjtdatf.sjtngkb
        FROM
          subq_sjtdatf
          LEFT JOIN subq_sjtdatfsub
            ON subq_sjtdatf.sjtkancd = subq_sjtdatfsub.sjtkancd
            AND subq_sjtdatf.sjtrei = subq_sjtdatfsub.sjtrei
          LEFT JOIN subq_sjtdatf3sub3
            ON subq_sjtdatf.sjtkancd = subq_sjtdatf3sub3.sjtkancd
            AND subq_sjtdatf.sjtrei = subq_sjtdatf3sub3.sjtrei
      )

      --【入院移動情報サブクエリ】患者ごとの入院日と退院日の履歴を抽出
      ,sub_nyutblljhist AS (
        SELECT 
          kancd,
          nyuday,
          taiday
        FROM
          nyutblfhist 
        WHERE
          kancd >= '0030000'
          AND kancd <= '0090000'
      )

      --【入院移動情報サブクエリ】入院患者の移動情報を抽出
      ,sub_idotb AS (
        SELECT 
          code,
          idodate,
          idotblf.byoto,
          byotom.kjnam,
          roomno
        FROM 
          idotblf 
            LEFT JOIN byotom 
              ON idotblf.byoto = byotom.byoto
        WHERE 
          tutikb IN (01,09) 
          AND ykkb != 9
          AND code >= '0030000'
          AND code <= '0090000'
      )

      --【入院移動情報メインクエリ】入院移動情報のサブクエリをまとめて、入院移動情報のメインクエリとする
      ,m_nyuido AS (
        SELECT 
          sub_nyutblljhist.kancd,
          sub_nyutblljhist.nyuday,
          sub_nyutblljhist.taiday,
          sub_idotb.idodate,
          sub_idotb.kjnam
        FROM
          sub_nyutblljhist
            LEFT JOIN sub_idotb
              ON sub_nyutblljhist.kancd = sub_idotb.code 
              AND sub_nyutblljhist.nyuday <= sub_idotb.idodate 
              AND sub_nyutblljhist.taiday >= sub_idotb.idodate
        WHERE
          kancd NOT LIKE'999%'
      )

      --【メインクエリ】カテオペ情報に入院情報を加えてデータを抽出する
      SELECT
        tb.sjtymd AS ymd,
        tb.sjtsttime AS sttime,
        tb.sjtkb AS sckb,
        tb.sjtkancd AS kancd,
        tb.kjnam,
        tb.sjtrei,
        tb.tkjryk AS sctitle,
        tb.sijidr,
        tb.tantonam,
        CASE
          WHEN tb.byoto IS NULL
            THEN '外来'
          WHEN tb.sjtngkb = 1
            THEN '外来'
          ELSE byoto
        END AS ngkb,
        CASE
          WHEN tb.sjtkb = 'カテ･アンギオ'
            THEN 11
          WHEN tb.sjtkb = 'オペ'
            THEN 0
          ELSE null
        END AS filmekbn
      FROM
        (
        SELECT
          m_kateope.sjtymd,
          m_kateope.sjtsttime,
          m_kateope.sjtkancd,
          m_kateope.kjnam,
          m_kateope.sjtrei,
          m_kateope.sjtkb,
          m_kateope.sjtroom,
          m_kateope.tnen,
          m_kateope.tkjryk,
          m_kateope.sijidr,
          m_kateope.tantonam,
          m_kateope.sjtngkb,
          m_nyuido.nyuday,
          m_nyuido.taiday,
          m_nyuido.idodate,
          m_nyuido.kjnam AS byoto,
          -- 移動情報が①日の間に複数あった場合に、一番最新の情報のみを取り出すためにnum項目をサブクエリ作成し、メインクエリのWHERE区で「1」を指定する
          ROW_NUMBER() OVER(PARTITION BY m_kateope.sjtymd, m_kateope.sjtkancd, m_kateope.sjtkb ORDER BY m_nyuido.idodate DESC) num
        FROM
          m_kateope
            LEFT JOIN m_nyuido
              ON m_kateope.sjtkancd = m_nyuido.kancd
              AND m_kateope.sjtymd >= m_nyuido.idodate
              AND m_kateope.sjtymd <= m_nyuido.taiday
        )tb
      WHERE
        tb.num = 1
      ORDER BY
        tb.sjtymd, tb.sjtsttime, tb.sjtkancd ASC
    SQL;
    $stmt = $this->pdo->prepare($ope);
    // bindPramでそれぞれ取得してきた変数を代入
    $stmt->bindParam(1,$tnen,PDO::PARAM_INT);
    $stmt->bindParam(2,$setday,PDO::PARAM_INT);
    // SQL結果を取得
    $stmt->execute();
    $opes = $stmt->fetchAll();

    return $opes;

  }

  private function getKensa($tnen, $setday) {
    /**
     * 検査予定を抽出するSQL
     */
    $kensa =<<<SQL
      -- 【検査予定情報サブクエリ】フィルム分類別に点数マスタを取り出すクエリ 
      WITH subq_tenm AS (
        SELECT
          f.tnen,
          f.tkey,
          f.tkjryk,
          f.tshinno,
          SUBSTRING(tensanteikbn2,6,2) AS filmekbn,
          SUBSTRING(tensanteikbn2, 17, 2) AS yoteikbn
        FROM
          tenmf f
            LEFT JOIN tensubm sub
              ON f.tkey = sub.tenkey
        WHERE
          tnen = ?
      )

      --【検査予定情報サブクエリ】予約時間を抽出する為、orddatfの自己リレーション用のクエリ
      ,subq_self_orddatf AS (
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
      ,subq_kantable AS (
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
      ,subq_userm AS (
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
      ,subq_namef AS (
        SELECT 
          RIGHT(namkey, 2) AS namkey,
          kjnam
        FROM
          namef
        WHERE
          namkey LIKE '69%'
      )

      --【検査予定情報サブクエリ】入院病棟を補完する為のクエリ
      ,subq_byoto AS (
        SELECT 
          ido.code,
          ido.byoto,
          CASE 
            WHEN ido.byoto = 01
              THEN '3F'
            WHEN ido.byoto = 04
              THEN '4F'
            ELSE '外来'
          END AS 'botoname',
          ido.idodate,
          nyu.nyuday,
          nyu.taiday
        FROM
          idotblf ido
            LEFT JOIN nyutblfhist nyu
              ON ido.code = nyu.kancd
              AND ido.idodate >= nyu.nyuday
              AND ido.idodate <= nyu.taiday
        WHERE
          tutikb IN ('01', '02', '09')
          AND ido.byoto != 00
          AND ido.ykkb != 9
      )

      --【検査予定情報メインクエリ】 orddatfで予約検査を抽出し、各サブクエリでデータを補完する
      , m_kensa AS (
        SELECT 
          DISTINCT orddatf.ymd AS ymd,
          subq_self_orddatf.yoyakutime AS sttime,
          subq_tenm.filmekbn,
          subq_namef.kjnam AS sckb,
          orddatf.kancd,
          subq_kantable.kjnam,
          subq_tenm.tkjryk AS sctitle,
          subq_userm.tantonam,
          orddatf.ngkb,
          orddatf.tencd,
          subq_tenm.yoteikbn,
          orddatf.drcd,
          orddatf.ordno,
          orddatf.syno,
          orddatf.syinno
        FROM
          orddatf
            LEFT JOIN subq_tenm
              ON orddatf.tencd = subq_tenm.tkey
            LEFT JOIN subq_self_orddatf
              ON orddatf.kancd = subq_self_orddatf.kancd
              AND orddatf.ymd = subq_self_orddatf.ymd
              AND orddatf.ordno = subq_self_orddatf.ordno
              AND orddatf.syno = subq_self_orddatf.syno
            LEFT JOIN subq_kantable
              ON orddatf.kancd = subq_kantable.code
            LEFT JOIN subq_userm
              ON orddatf.drcd = subq_userm.tantocd
            LEFT JOIN subq_namef
              ON subq_tenm.filmekbn = subq_namef.namkey
        WHERE 
          orddatf.bunrui2 = 3
          AND orddatf.ymd = ?
          AND orddatf.tencd != 'DELETE'
          AND orddatf.tencd != 000000
          AND orddatf.syinno != 999
          AND orddatf.syinno = 1
          AND subq_tenm.filmekbn IN ('11', '14', '30', '31', '32', '33', '34', '36', '44', '51')
          ANd orddatf.kancd >= '0030000'
          AND orddatf.kancd <= '0090000'
        /*ORDER BY 
          filmekbn,
          orddatf.kancd,
          orddatf.ordno,
          orddatf.syno,
          orddatf.syinno ASC*/
      )

      --【入院移動情報サブクエリ】患者ごとの入院日と退院日の履歴を抽出
      ,sub_nyutblljhist AS (
        SELECT 
          kancd,
          nyuday,
          taiday
        FROM
          nyutblfhist 
      )

      --【入院移動情報サブクエリ】入院患者の移動情報を抽出
      ,sub_idotb AS (
        SELECT 
          code,
          idodate,
          idotblf.byoto,
          byotom.kjnam,
          roomno
        FROM 
          idotblf 
            LEFT JOIN byotom 
              ON idotblf.byoto = byotom.byoto  
        WHERE 
          tutikb IN (01,09) 
          AND ykkb != 9
      )

      --【入院移動情報メインクエリ】入院移動情報のサブクエリをまとめて、入院移動情報のメインクエリとする
      ,m_nyuido AS (
        SELECT 
          sub_nyutblljhist.kancd,
          sub_nyutblljhist.nyuday,
          sub_nyutblljhist.taiday,
          sub_idotb.idodate,
          sub_idotb.kjnam
        FROM
          sub_nyutblljhist
            LEFT JOIN sub_idotb
              ON sub_nyutblljhist.kancd = sub_idotb.code 
              AND sub_nyutblljhist.nyuday <= sub_idotb.idodate 
              AND sub_nyutblljhist.taiday >= sub_idotb.idodate
        WHERE
          kancd NOT LIKE '999%'
      )


      --【メインクエリ】
      SELECT
        tb.ymd,
        tb.sttime,
        tb.sckb,
        tb.kancd,
        tb.kjnam,
        tb.sctitle,
        tb.tantonam,
        CASE
          WHEN tb.byoto IS NULL
            THEN '外来'
          WHEN tb.ngkb = 1
            THEN '外来'
          ELSE byoto
        END AS ngkb,
        tb.filmekbn,
        tb.ordno,
        tb.syno
      FROM
        (
        SELECT 
          m_kensa.ymd,
          m_kensa.sttime,
          m_kensa.sckb,
          m_kensa.kancd,
          m_kensa.kjnam,
          m_kensa.sctitle,
          m_kensa.tantonam,
          m_kensa.ngkb,
          m_nyuido.kjnam AS byoto,
          ROW_NUMBER() OVER(PARTITION BY m_kensa.ymd, m_kensa.kancd, m_kensa.sctitle ORDER BY m_nyuido.idodate DESC) num,
          m_kensa.filmekbn,
          m_kensa.ordno,
          m_kensa.syno
        FROM
          m_kensa
            LEFT JOIN m_nyuido
              ON m_kensa.kancd = m_nyuido.kancd
              AND m_kensa.ymd >= m_nyuido.idodate
              AND m_kensa.ymd <= m_nyuido.taiday
        ) tb
      WHERE
        tb.num = 1
      ORDER BY
        tb.ymd, tb.filmekbn , tb.sttime, tb.kancd ASC
    SQL;
    $stmt = $this->pdo->prepare($kensa);
    // bindPramでそれぞれ取得してきた変数を代入
    $stmt->bindParam(1,$tnen,PDO::PARAM_INT);
    $stmt->bindParam(2,$setday,PDO::PARAM_INT);
    $stmt->bindParam(3,$setday,PDO::PARAM_INT);
    // SQL結果を取得
    $stmt->execute();
    $kensas = $stmt->fetchAll();

    return $kensas;

  }
}