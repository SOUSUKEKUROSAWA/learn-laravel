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
- StartUp
  - `cd C:\Users\kuros\Documents\learn-laravel`
  - `docker-compose up -d`
  - http://127.0.0.1:8000/
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
## `npm run dev`が正常に終了しない問題
- 状況
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
- 状況
  - Laravelプロジェクトは正常にインストールされたものの，`localhost:8000`にアクセスしても接続できない
- 原因
  - Webサーバ（Apache）のDocumentRootが`/var/www/html`になっており，`\var\www\html\public\index.php`を見つけられなかったから
- 解決策
  - `RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf`
    - DocumentRootを`/var/www/html/public`に変更することでLaravelがpublicディレクトリ内の`index.php`を読み込んでアプリケーションを動作させることができるようになる
## フロントページ表示時にエラーが発生する問題
- 状況
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
- 状況
  - フロントページにはアクセスできるものの，その他のページにアクセスしようとしても404NotFoundエラーが発生してしまう
- 原因
  - Webサーバ（Apache）がデフォルト以外のページにアクセスできない設定になっていたから
- 解決策
  - `RUN a2enmod rewrite`
    - Laravelのデフォルト画面以外を表示させる場合には、Apacheのrewriteモジュールを有効にする必要がある
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
- 状況
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
- 状況
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
- 状況
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
- `php artisan make:model Profile -m`
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
- tinkerを使って手動でデータを挿入する
  - コード上に仮フォームを作る手間が省ける
- リレーション先のデータへのアクセス
  - `$profile->user;`
    - 注意
      - `$profile->user();`とすると親の`BelongsTo`メソッドへのアクセスになってしまう
## モデルクラス内で他のモデルクラスを用いるときに`App\<model>`とする必要がない理由
- すべてのモデルクラスの`namespace`は`App`であるため（つまり，同じ名前空間を共有しているため）
## 親モデルからリレーション先の子モデルへのデータのsaveがDBに反映されない問題
- 状況
```php
$user = App\User::find(1);
$user->profile->url = "freecodecamp.org";
$user->save();
$user->profile;
=> App\Profile {#4042
     id: "1",
     user_id: "1",
     title: "Cool Title",
     description: "Description",
     url: "freecodecamp.org", // saved?
     created_at: "2023-04-01 06:17:00",
     updated_at: "2023-04-01 06:17:00",
   }
// tinkerを再起動
$user = App\User::find(1);
$user->profile;
=> App\Profile {#4292
     id: "1",
     user_id: "1",
     title: "Cool Title",
     description: "Description",
     url: null, // but, not saved!
     created_at: "2023-04-01 06:17:00",
     updated_at: "2023-04-01 06:17:00",
   }
```
- 原因
  - 親モデルから`save`メソッドを実行しただけでは，子モデルのDBは変更されないこと
- 解決策
  - 親モデルで`push`メソッドを利用すると，再帰的に子モデルのDBも更新される
```diff
$user = App\User::find(1);
$user->profile->url = "freecodecamp.org";
- $user->save();
+ $user->push();
```
# Fetching the Record From The Database
## 存在しないユーザーのプロフィール画面にアクセスすると，404エラーではなく，破壊的なエラーが発生してしまう問題
- 状況
  - 404エラーではなく，以下のようなエラーが発生する
```
ErrorException (E_ERROR)
Trying to get property 'username' of non-object (View: /var/www/html/resources/views/home.blade.php)
```
- 原因
  - `User::find()`メソッドがエラーハンドリングを行っていなかったこと
- 解決策
```diff
- $user = User::find($user);
+ $user = User::findOrFail($user);
```
# Adding Posts to the Database & Many To Many Relationship
- `php artisan make:model Post -m`
- user情報が削除された場合，postsデータも同時に削除されるべき
  - カスケード
- form内の`enctype="multipart/form-data"`とは？
  - `enctype="application/x-www-form-urlencoded"`に設定されている場合
    - フォームフィールドからのデータは `"name=value&photo=value"`の形式でエンコードされ送信される
      - シンプルなテキスト入力にはうまく機能するが、写真のようなバイナリデータを正しく送信することができない
  - `enctype="multipart/form-data"`に設定されている場合
    - データは別々の部分で送信され、写真ファイルは別のバイナリストリームとして送信される
      - サーバーはデータを適切に処理し、写真が正しくアップロードされることが保証される
      - ファイルなどは`Illuminate/Http/UploadedFile`クラスとしてリクエストに含まれる
        - これにより，ファイルの保存・名前の変更・S3などへの配置が可能になる（後述）
```
POST /upload HTTP/1.1
Host: example.com
Content-Type: multipart/form-data; boundary=---------------------------1234567890

-----------------------------1234567890
Content-Disposition: form-data; name="name"

John Doe
-----------------------------1234567890
Content-Disposition: form-data; name="photo
filename="myphoto.jpg"
Content-Type: image/jpeg

[バイナリデータ]
-----------------------------1234567890--
```
- バリデーションルール
  - https://laravel.com/docs/5.8/validation#available-validation-rules
- `create`メソッド
```diff
$data = request()->validate([
    "caption" => ["required"],
    "image" => ["required", "image"],
]);
- $post = new \App\Post();
- $post->caption = $data["caption"];
- $post->save();
+ \App\Post::create($data);
```
## キャプションを投稿しようとすると`419 Page Expired`エラーが発生する問題
- 状況
  - postsデータのcreate画面からフォーム送信後，`419 Page Expired`エラーが発生し，正常にデータを送信できていない
- 原因
  - CSRFの対策を行っていなかったこと
    - CSRFエラー
      - ウェブアプリケーションでは，実際にウェブサイトを経由しなくても，データを送信することができてしまう．
        - そのため，エンドポイントに到達する権限を持つユーザーを制限する必要がある
      - Laravelは各フォームに固有のトークンを追加し，それらを検証することができる
        - リクエストの中に，もし，正しいトークンが含まれていればリクエストを受け付け，そうでなければ（トークンが正しくない，もしくはトークンがないならば），419エラーを吐く
          - トークンは1つのフォームにつき1つ割り振られる
            - リクエストごとに変化するわけではない
- 解決策
```diff
<form action="/p" enctype="multipart/form-data" method="post">
+     @csrf
~~~
</form>
```
実際のページソースを見るとトークンが生成されているのが分かる
```diff
<form action="/p" enctype="multipart/form-data" method="post">
-   @csrf
+   <input type="hidden" name="_token" value="llRSFvgZuY5l8lwiH5ZHt53gcS0S95eCt3b7sEeb">
    ~~~
</form>
```
## CaptionとImageを正しく入力して，フォームを送信してもエラーが発生してしまう問題
- 状況
  - フォームを送信したところ以下のエラーが発生
```
Illuminate \ Database \ Eloquent \ MassAssignmentException
Add [caption] to fillable property to allow mass assignment on [App\Post].
```
- 原因
  - fillableの項目を設定していないから
- 解決策
```diff
class Post extends Model
{
+   protected $guarded = [];
    ~~~
}
```
今回の場合，リクエストを各カラムを名前指定したうえでバリデーションを行い，その後DBに保存している．そのため，fillableの制約を解除しても問題ない（空の配列にすることでLaravelに「何も保護しなくても問題ないです」と伝えている）
# Creating Through a Relationship
## CaptionとImageを正しく入力して，fillableをオフにして，フォームを送信してもエラーが発生してしまう問題
- 状況
  - フォームを送信したところ以下のエラーが発生
```
Illuminate \ Database \ QueryException (23000)
SQLSTATE[23000]: Integrity constraint violation: 19 NOT NULL constraint failed: posts.user_id (SQL: insert into "posts" ("caption", "image", "updated_at", "created_at") values (New Caption Here, /tmp/phpq2KgvD, 2023-04-01 13:38:57, 2023-04-01 13:38:57))
```
- 原因
  - テーブルに必須の外部IDの情報をリクエストに含めていなかったこと
- 解決策
```diff
public function store()
{
    ~~~
-   \App\Post::create($data);
+   auth()->user()->posts()->create($data);
}
```
これにより，
- `auth()->user()`
  - 認証されたユーザーを取得
- `->posts()`
  - そのユーザーのpostsデータにアクセス
- `->create($data)`
  - postデータを作成
    - この際にLaravelが自動的にリレーションを解決し，そのpostデータに紐づくユーザーIDを追加してくれる

投稿は他人のユーザーをもって行うことはできないため，この書き方でユーザーを識別できる
## 派生：そもそも投稿画面や投稿リクエストを未ログインユーザーが行えてしまう問題
- 状況
  - 最終的なDBの更新は行えないように設計できたものの，投稿画面へのアクセスや投稿リクエストは未ログインユーザーでもできてしまっていた
- 原因
  - これらの動作に対する認証が実装されていなかったから
- 解決策
```diff
~~~
class PostController extends Controller
{
+   public function __construct()
+   {
+       $this->middleware("auth");
+   }
    ~~~
}
```
これにより，クラス内のメソッドを使う場合は，必ず認証されていなければいけなくなる
模試認証されていなければ，自動的にログインページに遷移するようになる
# Uploading/Saving the Image to the Project
- `store`メソッド
  - 第1引数
    - 保存場所のパス
      - `src\storage`配下の相対パスを指定
  - 第2引数
    - 保存に使用するドライバーを指定
      - 様々なドライバーがある
      - ローカルストレージは`public`ディレクトリを指定することになる
  - これにより，`src\storage`配下は以下のような構造になる
```
C:~\src\storage
├─app
│  └─public
│      └─uploads
|          └─GKLS9T8rKVF1NwlqUYJGWmiAVZBx6s81JDWwtXH1.svg
~~~

```
## 保存された画像にパスを用いてアクセスできない問題
- 状況
  - フォームから送信された画像データを`src\storage\app\public\uploads`ディレクトリに保存したものの，その画像のパスにGETリクエストを送っても画像にアクセスできない
- 原因
  - このディレクトリはstorageディレクトリの内側にあるため，ユーザーはアクセスできないこと
- 解決策
  - `php artisan storage:link`
    - `public/storage`から`storage/app/public`へのシンボリックリンクを作成するコマンド
      - 開発プロセスの中で一度だけ実行すればよい
        - `<host url>/storage/<upload path>`で画像にアクセスできるようになる
          - ***オリジナルのアップロードパスには`/storage/`の部分が含まれていないので，それはコントローラ内でコード上で追加する必要がある***
        - 実際にpublicディレクトリ内にstorageディレクトリが作成されるわけではない
## リダイレクト時点でエラーが発生する問題
- 状況
  - storeメソッド実行後，リダイレクト時にエラー発生
    - リダイレクト先のコントローラ内メソッド実行前にエラーが発生している
      - `return redirect("/profiles/1");`ではエラーは発生しないが，`return redirect("/profiles/" . auth()->user()->id());`ではエラーが発生する
```php
public function store()
{
    $data = request()->validate([
        "caption" => ["required"],
        "image" => ["required", "image"],
    ]);

    $imagePath = request("image")->store("uploads", "public");

    // create data with user_id
    auth()->user()->posts()->create([
        "caption" => $data["caption"],
        "image" => $imagePath,
    ]);

    return redirect("/profiles/" . auth()->user()->id());
}
```
```
UnexpectedValueException
The stream or file "/var/www/html/storage/logs/laravel-2023-04-02.log" could not be opened in append mode: failed to open stream: Permission denied
```
- 原因
  - スペルミス
- 解決策
```diff
public function store()
{
    ~~~
-   return redirect("/profiles/" . auth()->user()->id());
+   return redirect("/profiles/" . auth()->user()->id);
}
```
# Resizing Images with Intervention Image PHP Library
- `composer require intervention/image`
  - https://image.intervention.io/v2
- Imageファサードを利用するために`use Intervention\Image\Facades\Image;`を追加する必要がある
  - 画像の絶対パスを与えて，それに対してメソッドを適用してリサイズしていく
# Route Model Binding
- `\App\Post $post`
  - 自動でPostモデルでIDが`$post`と一致するリソースを取得し，`$post`に格納してくれる
  - リソースが存在しない場合のエラーハンドリングも事前に実装されている
# Editing the Profile
- アプリケーションの特定の部分へのアクセスを制限する方法
## 未ログインユーザーでもプロフィールを修正することができてしまう問題
- 状況
  - 未ログイン状態でプロフィール画面にアクセスしても，プロフィール編集の画面へのアクセスおよびプロフィール編集ができてしまう
- 原因
  - ユーザーのログインチェックを行っていないから
- 解決策
  - ルートモデルバインディングによって，渡されたユーザーデータを信用しないことによる保護
```diff
public function update(User $user)
    {
        $data = request()->validate([
            "title" => "required",
            "description" => "required",
            "url" => "url",
            "image" => "",
        ]);

-       $user->profile->update($data);
+       auth()->user()->profile->update($data);

        return redirect("/profiles/{$user->id}");
    }
```
# Restricting/Authorizing Actions with a Model Policy
- 上記の問題について，コントローラで認証チェックをするだけでは不十分で，そもそも見ろグイユーザーにEdit Profileの動線が見えてもいけない
  - これを実現するために「ポリシー」を使用する
- `php artisan make:policy ProfilePolicy -m Profile`
  - ポリシークラスを新規作成する
- Poricyクラスメソッド
  - 引数
    - 認証されたユーザークラスのインスタンス
    - 元となるモデルクラスのインスタンス
  - 返り値
    - `true/false`
      - そのメソッドを実行できるユーザーの条件を記述する
- `$this->authorized("<action>", <model class instance>)`と記述することでメソッドを実行する権限をチェックする
  - ただ，これだけだと，未ログインユーザーに「Edit Profile」ボタンが見えたまま
  - viewの`can`ディレクティブを利用してボタンを表示するための条件を追加する
- ここまでの施策によって，未ログインユーザーは
  - Edit Profileボタンが見えない
  - Editページにアクセスできない
  - 更新処理が行えない
    - また，ログインしていたとしても他のユーザーのプロフィール編集の動線にはアクセスできない
# Editing the Profile Image
- `array_merge(<target array>, <merging array>)`
  - `<merging array>`で指定した`<target array>`のキーの値を更新することができる
# Automatically Creating A Profile Using Model Events
- ユーザ登録した際に，プロフィールは作成されない
  - ユーザ登録と同時に空白のプロフィールを作成した方がいい
- モデルイベントを利用する
  - https://laravel.com/docs/5.8/eloquent#events
- `boot()`
  - 親モデルが起動する度に呼び出される
  - `created`
    - 親モデルのインスタンス作成後に毎回呼び出される
      - `creating`との違い
        - 親モデルのインスタンス作成前に毎回呼び出される
      - クロージャ
        - 作成されたモデルが実際に提供されるという特徴がある
# Default Profile Image
- ユーザ登録時に空のプロフィールが自動作成されるが，画像はエラー表示になってしまう
  - デフォルトの画像を設定しておいた方がユーザにとってわかりやすい
    - https://www.shoshinsha-design.com/2020/05/%E3%83%8E%E3%83%BC%E3%82%A4%E3%83%A1%E3%83%BC%E3%82%B8%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B3-%E3%83%94%E3%82%AF%E3%83%88-no-image-icon-2/.html
## プロフィール編集時画像をアップロードせずに更新するとエラーが発生する問題
- 状況
  - ログイン
  - プロフィール編集
  - 画像を選択せずに更新
  - 以下のエラーが発生
```
ErrorException (E_NOTICE)
Undefined variable: imagePath
```
- 原因
  - リクエストには常に画像がセットされているという間違った前提に基づいて処理を実装していたこと
- 解決策
```diff
public function update(User $user)
{
    $this->authorize("update", $user->profile);

    $data = request()->validate([
        "title" => "required",
        "description" => "required",
        "url" => "url",
        "image" => "",
    ]);

    if (request("image")) {
        $imagePath = request("image")->store("profile", "public");

        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
        $image->save();

+       $imageArray = ["image" => $imagePath];
    }

    auth()->user()->profile->update(array_merge(
        $data,
-       ["image" => $imagePath]
+       $imageArray ?? [] // 画像がセットされていない場合はなにもマージされない
    ));

    return redirect("/profiles/{$user->id}");
}
```
# Follow/Unfollow Profiles Using a Vue.js Component
- フォローボタンを押したときにページ全体をリロードしない方がいい
  - Vue.jsを用いる
- `src\resources\js\app.js`にてコンポーネントの名前とパスを定義している
- vueの注意点
  - HTMLタグは並列に配置できない
    - 必ず１つのルートタグが必要
- followボタンクリック時のアクション
  - axiosライブラリを利用する
    - API呼び出しが非常に簡単になる
    - Laravelにはデフォルトで用意されている
  - 実装方法
    - ボタンクリック時に実行するメソッドをボタンタグ内に記述
    - そのメソッド内でaxiosを用いてpostリクエストを送信
    - 受け取ったリクエストをもとにDBへデータ登録
    - 受け取ったレスポンスをもとに再レンダリング
- propsの受け渡し
  - HTMLタグを通して，VueコンポーネントにProosを渡せる
## `npm run watch`を実行するとエラーが発生する問題
- 状況
  - `src\resources\js\components\FollowButton.vue`を編集
  - `npm run watch`
  - 以下のエラーが発生
```
FollowButton.vue Doctor
❗ Incorrect Target
Target version mismatch. You can specify the target version in vueCompilerOptions.target in tsconfig.json / jsconfig.json. (Expected "target": 2.7)

vue version: 2.7.14
tsconfig target: 3 (default)
vue: c:\Users\kuros\Documents\learn-laravel\src\node_modules\vue\package.json
tsconfig: Not found
vueCompilerOptions:
{
  "extensions": [
    ".vue"
  ]
}
Have any questions about the report message? You can see how it is composed by inspecting the source code.
```
- 原因
  - vueのバージョンと`vueCompilerOptions.target`のバージョンに互換性がなかったこと
  - ブラウザ及びアプリケーションに過去のキャッシュが残ってしまっていたこと
- 解決策
  - 下記のような`jsconfig.json`をルートディレクトリに作成する
  - `npm run watch`
  - `php artisan cache:clear`及びブラウザのキャッシュクリア
    - ブラウザのキャッシュクリア方法（Google Chromeの場合）
      - ブラウザを開きます。
      - 右上の三つの点が並んでいるアイコンをクリックします。
      - 「その他のツール」を選択し、「クリアブラウジングデータ」を選択します。
      - クリアする項目と期間を選択し、「データを消去」をクリックします。
`jsconfig.json`:
```json
{
  "compilerOptions": {
    "target": "es2017",
    "module": "esnext",
    "moduleResolution": "node",
    "baseUrl": ".",
    "paths": {
      "@/*": ["src/*"]
    },
    "lib": [
      "esnext",
      "dom",
      "dom.iterable",
      "scripthost"
    ]
  },
  "vueCompilerOptions": {
      "target": 2.7
  }
}
```
## `npm run watch`を実行しても自動コンパイルが行われない問題
- 状況
  - vueファイルを変更
  - `npm run watch`
  - vueファイルを変更
  - 保存
  - 自動コンパイルが行われず
- 原因
  - `watch`コマンドが常に動作しているわけではなかったこと
- 解決策
  - `npm run watch-poll`
    - 強制的に一定間隔（例：1000ms）でファイルが変更されたかどうかをチェックするようになる
- 参考
  - https://stackoverflow.com/questions/44127688/difference-between-npm-run-watch-and-npm-run-watch-poll
# Many To Many Relationship
- プロフィールは多くのユーザーにフォローされる可能性があり，ユーザーは多くのプロフィールをフォローすることができる
- `php artisan make:migration create_profile_user_table --create profile_user`
  - ピボットテーブルの命名規則
    - 接続する2つのテーブル名をアルファベット順に並べる
    - すべて小文字・単数形にする
    - 2つのテーブル名の間に`_`を追加する
- `toggle(<column name>)`
  - 指定したBoolean型カラムの値を反転させる
  - `auth()->user()->following()->toggle($user->profile);`の場合
    - `auth()->user()->following()`
      - 認証ユーザとプロフィール間の多対多の関係を表す`belongsToMany`コレクションオブジェクトが返される
    - `->toggle($user->profile)`
      - プロフィールがフォローされていない場合`false`は認証されたユーザーのフォロー一覧にプロフィールを追加`true`
      - すでにフォローされている場合`true`は一覧から削除`false`
- followしているのかしていないのかをユーザーに明示的に示す
  - デフォルトをどのように規定するのか
    - そのユーザーがフォローしているプロフィールの中に，アクセス先のプロフィールと同じものがあるかどうかのチェック結果を`true/false`で渡す
# Laravel Telescope
- https://laravel.com/docs/5.8/telescope
  - laravelのデバッグアシスタント
    - アプリケーションに直接接続できる非常に完全なツールセット
- http://127.0.0.1:8000/telescope
  - Queries
    - クエリのログ
    - 一つ一つのクエリの詳細も確認できる
# Showing Posts from Profiles The User Is Following
- インスタグラムでは，ログイン後，フォローしているユーザの投稿が新しい順で見れるようになっている
- `pluck("<column name>")`
  - コレクションインスタンスから特定のカラムだけ抽出した配列を新たに作成する
## アプリ全体の実行速度が非常に遅い問題
- 状況
  - ページ遷移に非常に長い時間がかかっていた．dockerが使えるリソースを増やしても改善しなかった．また，Laravel以外のアプリ（Djangoアプリなど）では，通常の速度でページ遷移出来ていたので，何か設定に問題があるようだったが，原因が分からづにいた．
- 原因
  - OPcacheが有効になっていなかったこと
- 解決策
  - opcache.iniの作成
  - Dockerfileに`RUN docker-php-ext-install opcache`及び`COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini`を追加
  - `docker compose up -d --build`
  - ただ，これでもだいぶ遅いので，他にも原因がありそう
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
- `A ?? B`
  - まずはA，AがなければBを表示する
- `Ctrl-e`（キーボードショートカット）
  - ファイル検索
- `<model name>::truncate();`
  - 指定したテーブル内のデータをすべて削除する
- `compact("<name>",...)`
  - `<name>`という名前で`$<name>`変数をviewに渡す
- `npm run watch`
  - プロジェクト内で一度だけ実行すれば，その後はすべてのファイルを監視し，変化がある度にすべてのコードが再コンパイルされ，ブラウザに表示される
    - 開発中に使用する物
- `npm install`
  - `package.json`に従ってパッケージをインストールする
    - `node_modules`ディレクトリなどバージョン管理していないディレクトリの変更をUndoすることもできる
      - `package.json`（バージョン管理されている）のバージョンをもとに戻す
      - `node_modules`ディレクトリを削除
      - `npm install`
- `latest()`
  - =`orderBy("created_at", "desc")`