# https://youtu.be/ImtZ5yENzgE
# Introduction
- https://www.instagram.com/freecodecamp/
- インスタグラムクローン
  - 投稿機能
  - フォロー機能
  - プロフィール編集機能
    - 画像投稿機能
    - 画像のリサイズ機能
  - 認証機能
# Installing Laravel
- dockerを利用したLaravelの環境構築
  - `Dockerfile`
    - `apt-get`
      - Ubuntuを含むDebianベースのLinuxディストリビューションで使用されるパッケージマネージャー
        - OSのリポジトリからソフトウェアパッケージと依存関係をインストール、更新、および削除するために使用される
      - `update`
        - ローカルパッケージデータベースを最新バージョンに更新
      - `install`
        - ***必要な依存関係は全てDockerfileに記述しておくのがベストプラクティス***
    - `apt-get install libzip-dev`
      - Composerパッケージ管理などのさまざまな機能のためにLaravelで必要とされる、PHPでのZIPアーカイブのサポートを有効にするために必要
        - これがないと，Composer を使用してパッケージをインストールまたは更新することができない
    - `apt-get install libpng-dev`
      - PHPでPNG画像ファイルのサポートを有効にするために必要
        - 画像操作のためにLaravelのIntervention Imageライブラリで必要とされる
          - これがないと，Intervention ImageはPNG画像を処理できない
    - `apt-get install libfreetype6-dev`
      - PHPでTrueTypeフォントのサポートを有効にするために必要  
        - 画像操作のためにLaravelのIntervention Imageライブラリで必要とされる
          - これがないと，Intervention Imageはフォントを処理できない
    - `docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/`
      - これがないとGDライブラリ（Intervention Image）が`libfreetype6-dev`および`libjpeg-dev`ライブラリを使用するように設定できなくなり，TrueTypeフォント，JPEGイメージの処理ができなくなる
      - `--with-freetype-dir=/usr/include/`
        - `libfreetype6-dev`ライブラリの場所を示す
      - `--with-jpeg-dir=/usr/include/`
        - `libjpeg-dev`ライブラリの場所を示す
    - `docker-php-ext-install zip gd`
      - Laravelおよびその依存関係で必要とされる重要なPHP拡張機能をインストールおよび有効化する
        - `zip`
          - PHPがZIPアーカイブと連携できるようにします。
          - Composerに必要なやつ
        - `gd`
          - 画像の処理や操作に必要な関数をPHPに提供
          - Intervention Imageなどのライブラリを使用する場合、特に画像を扱うためにLaravelで必要
    - `# Install Node.js for npm`
      - フロントエンド開発（JS）で必要
        - `npm`コマンドを使う
    - `# Install composer`
      - Laravel関連のパッケージマネージャ
    - `# Change the Apache document root`
      - DocumentRootを`/var/www/html/public`に変更することでLaravelがpublicディレクトリ内の`index.php`を読み込んでアプリケーションを動作させることができるようになる
    - `# Enable URL rewriting, redirection etc.`
      - Laravelのデフォルト画面以外を表示させる場合には、Apacheのrewriteモジュールを有効にする必要がある
- 手順
  - `docker-compose up -d`
    - Laravelプロジェクトを作り直す場合
      - `docker exec -it learn-laravel-app-1 bash`
      - `composer create-project --prefer-dist laravel/laravel . "5.8.*"`
        - `--prefer-dist`
          - ComposerにLaravelパッケージの事前パッケージ化バージョンをダウンロードして使用するよう指示する
            - 個々のファイルをすべてダウンロードするよりも高速である場合があるため
      - `chown -R www-data:www-data /var/www/html/storage`
        - `/var/www/html/storage`ディレクトリの所有権を通常`www-data`というApacheユーザーに変更
          - アプリケーションは`/var/www/html/storage`ディレクトリに書き込むための権限を持つようになる
            - `storage`ディレクトリ
              - アプリケーションが操作中に読み書きする必要があるログ、キャッシュ、セッションデータなどの重要なファイルが含まれている
                - セキュリティ上の理由から、これらのファイルにはアプリケーション自体のみがアクセスでき、サーバー上の他のユーザーやプロセスはアクセスできないことが重要
  - http://localhost:8000/
# First look at the project
- `composer.json`
  - プロジェクトのアセット（プロジェクトに必要な情報）を保持するファイル
  - composerはこのファイルをもとにパッケージのインストールと依存関係の解決を行う
# Intro to `php artisan`
- Thinkerというものを使用して，アプリケーション全体に様々なことを実行できるようにするCLI
  - Laravelに同梱されている
- コマンドを基本的に名前空間を持っている
  - `<parent command>:<child command>`
# Generating login flow with make:auth
- `php artisan make:auth`
  - このコマンド一つで認証機能が設定される
# Setting Up the Front End with Node and NPM
- Laravelにはバックエンドの機能と同様にフロントエンドに関する機能も搭載している
  - Bootstrap（TwitterBootstrap）とVue.jsがデフォルトで組み込まれている
    - ただし，コンパイルを実行する必要はある
- 手順
  - node.jsをインストールする
    - バージョン18.xではエラーが発生した．
      - ***バージョン14.xをインストールしたところエラーは発生しなくなった***
  - `npm install`
    - `package-lock.json`が作成される
  - `npm run dev`
    - コンパイルを行うコマンド
      - Webpackを通じて行われる
    - 何か問題が生じた場合
      - `node_modules`ディレクトリを削除してもう一度やり直す
    - これにより，`src\public\js\app.js`と`src\public\css\app.css`にコンパイルされたフロントエンドのコードが書き込まれる
      - `src\resources\js\app.js`と`src\resources\sass\app.scss`はコンパイル前の実際に編集出来るファイル
- `src\resources\views\home.blade.php`はログイン後に表示されるビュー
# Migrations and Setting Up SQLite
- マイグレーションファイル
  - DBを作成するように指示するために必要な情報・命令の全てを保持したファイル
  - これがあることによって，手動でDBを変更する必要がなく，チーム全員が同じ手順でDBをセットアップできる
- SQLiteを利用
  - 空の`src\database\database.sqlite`を作成
  - `src\.env`ファイル内のDB関連の環境変数を変更
- `php artisan migrate`
# Designing the UI from Instagram
# Adding Username to the Registration Flow
# Creating the Profiles Controller
# RESTful Resource Controller
# Passing Data to the View
# Adding the Profiles Mode, Migration and Table
# Adding Eloquent Relationships
# Fetching the Record From The Database
# Adding Posts to the Database & Many To Many Relationship
# Creating Through a Relationship
# Uploading/Saving the Image to the Project
# Resizing Images with Intervention Image PHP Library
# Route Model Binding
# Editing the Profile
# Restricting/Authorizing Actions with a Model Policy
# Editing the Profile Image
# Automatically Creating A Profile Using Model Events
# Default Profile Image
# Follow/Unfollow Profiles Using a Vue.js Component
# Many To Many Relationship
# Calculating Followers Count and Following Count
# Laravel Telescope
# Showing Posts from Profiles The User Is Following
# Pagination with Eloquent
# N + 1 Problem & Solution
# Make Use of Cache for Expensive Query
# Sending Emails to New Registered Users
# Wrapping Up
# Closing Remarks & What's Next In your Learning

---

# Command Tips
- `php artisan key:generate`
  -  Laravelアプリケーションの暗号化に使用されるアプリケーションキーを自動的に生成する
    - アプリケーションでのセッション、トークン生成、パスワードリセットリンクの生成など、Laravel内の重要な暗号化タスクに使用される
- `docker exec -it <container name> php artisan <additional command>`
  - コンテナ外からワンライナーでコンテナ内に入り，`php artisan`系コマンドを実行する
- `php artisan`
  - このコマンドで使用できる追加コマンドの一覧が表示される
- `ls -ld <directory path>`
  - `<directory path>`ディレクトリの所有権を確認する
    - その内容ではなくディレクトリ自体についての情報を表示するコマンド
    - コマンドの出力
      - ディレクトリの権限
      - 所有者
      - グループ所有者
- `php artisan serve`
  - 開発用サーバーを起動し，ローカルホストへ接続する
    - ただし，Dockerにおける開発では，`docker-compose.yml`の設定により，コンテナが起動中は常にポートが公開されているため，このコマンドを実行しなくてもウェブページを表示できる