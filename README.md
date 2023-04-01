# https://youtu.be/ImtZ5yENzgE
# Introduction
- Goal
  - https://www.instagram.com/freecodecamp/
- GitHub
  - https://github.com/coderstape/freeCodeGram
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
## `npm run dev`が正常に終了しない問題
- 問題点
  - `npm run dev`を実行すると以下のエラーが発生する
```
Error: error:0308010C:digital envelope routines::unsupported
~~~
```
- 原因
  - node.jsのバージョンとLaravelのバージョンの依存関係からくる問題だと思われる
- 解決策
  - バージョン14.xのnode.jsをインストールする
## アプリケーションのフロントページが表示されない問題
- 問題点
  - Laravelプロジェクトは正常にインストールされたものの，`localhost:8000`にアクセスしても接続できない
- 原因
  - Webサーバ（Apache）のDocumentRootが`/var/www/html`になっており，`\var\www\html\public\index.php`を見つけられなかったから
- 解決策
  - `RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf`
    - DocumentRootを`/var/www/html/public`に変更することでLaravelがpublicディレクトリ内の`index.php`を読み込んでアプリケーションを動作させることができるようになる
## フロントページ表示時にエラーが発生する問題
- 問題点
  - フロントページへのGETリクエストを送ると以下のエラーが発生する
```
UnexpectedValueException
The stream or file "/var/www/html/storage/logs/laravel.log" could not be opened in append mode: failed to open stream: Permission denied The exception occurred while attempting to log: The stream or file file_put_contents(/var/www/html/storage/framework/views/d21bc1965d8c501e5e18921c4eb8ea6ec1e5686e.php): failed to open stream: Permission denied Context: {"exception":{}} Context: {"exception":{}} http://localhost:8000/
```
- 原因
  - アプリケーションがストレージディレクトリに書き込む権限がないこと
- 解決策
  - `chown -R www-data:www-data /var/www/html/storage`
    - storageディレクトリの所有権をアプリケーションユーザー（`www-data`）に変更する
## フロントページ以外のページにアクセスできない問題
- 問題点
  - フロントページにはアクセスできるものの，その他のページにアクセスしようとしても404NotFoundエラーが発生してしまう
- 原因
  - Webサーバ（Apache）がデフォルト以外のページにアクセスできない設定になっていたから
- 解決策
  - `RUN a2enmod rewrite`
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
      - `chmod -R 777 /var/www/html/storage`
        - ストレージディレクトリのパーミッションを誰でも書き込み可能に設定し、アプリケーションがディレクトリに書き込むことができるようにします。
        - register機能を実装するためにこの設定が必要になった
        - 注意
          - パーミッションを誰でも書き込み可能に設定することはセキュリティ上のリスクになるため、通常はディレクトリの所有権を設定することが推奨されます。
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
  - マイグレーションファイルをもとに，実際にDBが作成される
- `src\config\database.php`
  - DB設定ファイル
## ユーザー情報の登録のためのDB書き込みができない問題
- 問題点
  - registerページからユーザー情報を登録しようとすると以下のエラーが発生する
```
Illuminate \ Database \ QueryException (HY000)
SQLSTATE[HY000]: General error: 8 attempt to write a readonly database (SQL: insert into "users" ("name", "email", "password", "updated_at", "created_at") values (Test User, test@test.com, $2y$10$rG32NH8l0T7qaJzcRTMGE.X3vzv94BK8lOpfoT82ib71jD0SZ9e3W, 2023-03-30 04:42:15, 2023-03-30 04:42:15))
```
- 原因
  - `database.sqlite`が存在する親ディレクトリである`database`ディレクトリに書き込み権限がないこと
- 解決策
  - `chown -R www-data:www-data /var/www/html/database`
    - databaseディレクトの所有権の変更
      - sqliteへのデータ登録の際，`src\database\database.sqlite`に書き込みを行うが，Webサーバ（Apache）はこのファイル自身だけでなく，親ディレクトリの`src\database`にも書き込み権限を持っている必要がある
# Designing the UI from Instagram
- `public`ディレクトリ
  - Webサーバが最初にアクセスする場所
    - 他のユーザーはサイトにアクセスしてもpublicディレクトリの中にしかいない
      - アプリケーション上のpathは`~/public/`以降を記述すればよい
- `col-$`クラス
  - bootstrapでグリッドを使用して幅を指定する方法
    - col-12の内，どのくらい幅を取るか選択することができる
- inspect
  - Webページの画像上で右クリックをして`inspect`をクリックするとそのページのHTML情報が表示される
    - そこから画像のURLなどを取得することができる
# Adding Username to the Registration Flow
- Controller
  - データに対するアクションを実行する
- Views
  - 単にデータを正しく配置する
- Tinker
  - バックエンドを介してアプリケーションと対話できる
## ControllerでもDBでもユニークバリデーションを行う理由
- ControllerのユニークバリデーションはPHPレベルで行っており，DBに直接クエリが飛んできた場合を考えてDBでもユニークバリデーションを行っておく必要がある
## マイグレーションファイルを変更したにもかかわらず，DBに変更が反映されない問題
- 問題点
  - マイグレーションファイルにて新たにカラムを追加し，アプリケーションを実行してDBへデータを登録したものの，追加したはずのカラムが追加できていなかった
- 原因
  - マイグレーションファイルを変更しただけで，DBは変更されていなかったから
  - usernameをDBへ追加可能にするための設定を行っていなかったから（fillable担ていなかったから）
- 解決策
  - `php artisan migrate:fresh`
    - このコマンドにより，実際にDBを再作成する
      - 注意
        - ***再作成（一度すべてのテーブルを削除）するため，もともとDBに入っていたデータは消えてしまう***
  - 該当テーブルのモデルクラス内（`User.php`）の`$fillable`に該当カラム（`username`）を追加する
    - なぜいちいち`$fillable`に追加しなくてはいけないのか
      - 悪意のあるユーザーがDBに配列データに紛れて不正なデータを送信するのを防ぐため
        - 配列にまとめて一気に登録する場合は常に`fillable`のチェックがかかる
# Creating the Profiles Controller
- ホームページはログインユーザーしか見れないようになっているが，本物のインスタグラムでは未ログインでも閲覧は可能になっている
- `php artisan make:controller ProfileController`
# RESTful Resource Controller
- https://laravel.com/docs/5.8/controllers#resource-controllers

| Verb      | Path                    |	Action  | Route Name    |
| ---       | ---                     | ---     | ---           |
| GET       |	`/photos`               | index   | photo.index   |
| GET       |	`/photos/create`        |	create  | photo.create  |
| POST      | `/photos`               |	store   | photo.store   |
| GET       |	`/photos/{photo}`       | show	  | photo.show    |
| GET       |	`/photos/{photo}/edit`  | edit    | photo.edit    |
| PUT/PATCH |	`/photos/{photo}`       | update  | photo.update  |
| DELETE    | `/photos/{photo}`       | destroy | photo.destroy |
- この規則に従うことでコントローラは軽量になり，ETC（Easier To Change）になる
# Passing Data to the View
## コード修正後から画像が正しく表示されない問題
- 問題点
  - アプリケーションのフロントページのルーティングを`/home`から`/profiles/{user}`に変更したところ，それまで正常に表示されていた画像が表示されなくなった
- 原因
  - ルーティングの変更により，ビュー内の画像のURLも変更されていたこと
    - Before
      - http://127.0.0.1:8000/svg/freeCodeCampLogo.svg
    - After
      - http://127.0.0.1:8000/profiles/svg/freeCodeCampLogo.svg
- 解決策
```diff
- <img src="svg\freeCodeCampLogo.svg">
+ <img src="\svg\freeCodeCampLogo.svg">
```
# Adding the Profiles Mode, Migration and Table
- `php artisan make:model`
- Eloquent
  - LaravelにおけるフレームワークのDB層の呼称
  - 裏側でクエリをフェッチ（呼び出す）する実装となる層
    - これにより，開発者はDBの種類に依存せずに開発を行える
- profilesテーブルはusersテーブルと1対1の関係を持つ
- マイグレーションファイル
  - text型とstring型
    - text型
      - 改行を含むような長めの文字列
  - upメソッドとdownメソッド
    - downメソッド
      - upメソッドで実行したことの逆を実行する
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
- `chown -R <owner>:<group> <path>`
  - 指定したパスにあるファイルまたはディレクトリの所有者及びグループを変更する
    - パーミッションを直接変更するより安全に権限設定を行える
- `chmod -R <permission> <path>`
  - 指定したパスにあるファイルまたはディレクトリの権限を変更する
- `Ctrl-d`（キーボードショートカット）
  - 選択した文字列と一致する文字列を同時修正することができるコマンド
    - `Ctrl-f`よりも素早く置換できる
- `Ctrl-f`（キーボードショートカット）
  - 文字列置換オプションを開く
    - 選択範囲内限定での置換もできる
      - `Ctrl-d`よりも確実に置換できる
- `Ctrl-c`+`Ctrl-v`（キーボードショートカット）
  - 選択範囲を指定しなくてもその行全体をコピーできる
  - 貼り付けも自動で一つ下の行に貼り付けされる
- `php artisan help <command>`
  - して下コマンドに関するヘルプを参照できる
- `dd(<code>);`
  - 引数に与えたコードを実行した結果を表示し，その時点で処理を一時停止する
- `php artisan make:model <name> -m`
  - モデル名を指定してモデルクラスを作成する
  - 同時にそのモデル用のマイグレーションファイルも作成される