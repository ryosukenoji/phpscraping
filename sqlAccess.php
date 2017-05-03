<?php
function accessSQL($table){
    $host = "localhost";
    $db = "test";
    // データベースに接続するために必要なデータソースを変数に格納
    // mysql:host=ホスト名;dbname=データベース名;charset=文字エンコード
    $dsn = 'mysql:host='.$host.';dbname='.$db.';charset=utf8';

    // データベースのユーザー名
    $user = 'root';

    // データベースのパスワード
    $password = 'root';

// tryにPDOの処理を記述
    try {

        // PDOインスタンスを生成
        $dbh = new PDO($dsn, $user, $password);

        $prefecture = [];
        $stmt = $dbh->query("SELECT * FROM $table");
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }
        return $items;




// エラー（例外）が発生した時の処理を記述
    } catch (PDOException $e) {

        // エラーメッセージを表示させる
        echo 'データベースにアクセスできません！' . $e->getMessage();

        // 強制終了
        exit;

    }
}

function insertSQL($arg){
    $host = "localhost";
    $db = "test";
    // データベースに接続するために必要なデータソースを変数に格納
    // mysql:host=ホスト名;dbname=データベース名;charset=文字エンコード
    $dsn = 'mysql:host='.$host.';dbname='.$db.';charset=utf8';

    // データベースのユーザー名
    $user = 'root';

    // データベースのパスワード
    $password = 'root';

// tryにPDOの処理を記述
    try {

        // PDOインスタンスを生成
        $dbh = new PDO($dsn, $user, $password);


        // INSERT文を変数に格納
        $sql = "INSERT INTO itemList (name, url,suburbID) VALUES (:name, :url,:suburbID)";
// 挿入する値は空のまま、SQL実行の準備をする
        $stmt = $dbh->prepare($sql);
// foreachで挿入する値を1つずつループ処理
        foreach ($arg as $val) {
            var_dump($val);

            // 連想配列のキーを :name に、値を :population にセットし、executeでSQLを実行
            $stmt->execute(array(':name' => $val['name'], ':url' => $val['url'], ':suburbID' => $val['suburbID']));

        }


// 登録完了のメッセージ
        echo '登録完了しました<br>';


// エラー（例外）が発生した時の処理を記述
    } catch (PDOException $e) {

        // エラーメッセージを表示させる
        echo 'データベースにアクセスできません！' . $e->getMessage();

        // 強制終了
        exit;

    }
}
