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

予定されている検査と手術を一画面で確認でき、分類ごとにタブで切り替え可能患者の詳細画面

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

## 環境

<!-- 言語、フレームワーク、ミドルウェア、インフラの一覧とバージョンを記載 -->

| 言語・フレームワーク  | バージョン   |
| --------------------- | ------------ |
| PHP                   | 8.1.13       |
| Apache                | 2.4.54       |
| SQL Server            | 10.50.4302.0 |

その他のパッケージのバージョンは pyproject.toml と package.json を参照してください

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
