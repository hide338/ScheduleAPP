<div id="top"></div>

## 使用技術一覧

<!-- シールド一覧 -->
<p style="display: inline">
  <!-- フロントエンド -->
  <img src="https://img.shields.io/badge/-Javascript-000000.svg?logo=javascript&style=for-the-badge">
  <img src="https://img.shields.io/badge/-Sass-000000.svg?logo=sass&style=for-the-badge">
  <img src="https://img.shields.io/badge/-Bootstrap-000000.svg?logo=bootstrap&style=for-the-badge">
  <!-- バックエンド -->
  <img src="https://img.shields.io/badge/-Php-000000.svg?logo=php&style=for-the-badge">
  <!-- ミドルウェア一覧 -->
  <img src="https://img.shields.io/badge/-Apache-D22128.svg?logo=apache&style=for-the-badge">
  <img src="https://img.shields.io/badge/-SQL%20Server-666666.svg?logo=&style=for-the-badge">
  <!-- インフラ一覧 -->
  <img src="https://img.shields.io/badge/-Windows%20server-0078D6.svg?logo=windows&style=for-the-badge">
</p>

## 目次

1. [システム詳細](#システム詳細)
2. [環境](#環境)
3. [ディレクトリ構成](#ディレクトリ構成)

## システム名

ScheduleApp -検査・手術予定表-

<!-- プロジェクトについて -->

## システム詳細

予約された検査と手術の予定を一画面で閲覧できるシステム

予定されている検査と手術を一画面で確認でき、日別・週別・月別の表示切替、分類ごとのタブ切替が可能です。

また、患者詳細画面を表示すれば、禁忌情報や禁忌情報、オーダー内容が一画面で確認することが可能です。

### 機能一覧

- 一覧表示機能
- 日別・週別・月別に表示の切り替え
- 検査分類ごとの絞り込み
- ミニカレンダーで日付の選択
  - 日別・週別のみ
- 患者ごとの詳細情報を閲覧できる機能
  - 患者の個人情報
  - 禁忌情報
  - 感染症情報
  - オーダー内容

<p align="right">(<a href="#top">トップへ</a>)</p>

### システムイメージ図

- 日別画面
   ![日別](https://github.com/hide338/ScheduleApp/assets/93624688/1ff93fa3-a365-49b7-8a58-c69622e6cc45)
- 週別画面
   ![週別](https://github.com/hide338/ScheduleApp/assets/93624688/f0635f68-302b-4d06-a592-dc0863778105)
- 月別画面
   ![月別](https://github.com/hide338/ScheduleApp/assets/93624688/ecdf3972-fb55-470f-90aa-cb9f6d32c86d)
- 患者詳細画面
   ![患者詳細](https://github.com/hide338/ScheduleApp/assets/93624688/84a9750e-9ceb-47bd-aa6d-46eb6169cceb)

<p align="right">(<a href="#top">トップへ</a>)</p>

## 環境

<!-- 言語、フレームワーク、ミドルウェア、インフラの一覧とバージョンを記載 -->

| 言語・フレームワーク  | バージョン   |
| --------------------- | ------------ |
| PHP                   | 8.1.13       |
| Apache                | 2.4.54       |
| SQL Server            | 10.50.4302.0 |

<p align="right">(<a href="#top">トップへ</a>)</p>

## ディレクトリ構成

.
│  daylist.php
│  details.php
│  index.php
│  monthlist.php
│  weeklist.php
│
├─app
│      config.php
│      Database.php
│      Date.php
│      Schedule.php
│      Utils.php
│
├─css
│      riset.css
│      style.css
│      style.css.map
│
├─img
│      a+.png
│      a-.png
│      ab+.png
│      ab-.png
│      b+.png
│      b-.png
│      blood-type.png
│      blood-type_10008951.png
│      btn-calender.png
│      calendar&time.png
│      calendar.png
│      calendar_red.png
│      folder.png
│      man.png
│      o+.png
│      o-.png
│      woman.png
│
├─js
│      calendar.js
│      details.js
│      main.js
│
└─scss
        style.scss
