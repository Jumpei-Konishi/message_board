<?php
//データベース情報等の読み込み
require_once("data/db_info.php");

//データベースへ接続、データベース選択
$s = mysqli_connect($SERV,$USER,$PASS) or die("失敗しました");
mysqli_select_db($s, $DBMM);

//スレッドグループ番号を取得し(gu)$gu_dに代入
$gu_d = $_GET["gu"];

//$gu_dに数字以外が含まれていたら処理を中止
if(preg_match("/[^0-9]/", $gu_d)) {
    print <<<eot1
        不正な値が入力されています<br>
        <a href="keizi_top.php">ここをクリックしてスレッド一覧に戻ってください</a>
eot1;

//$gu_dに数字以外が含まれていない、正常な値での処理
} elseif(preg_match("/[0-9]/", $gu_d)) {
    //名前とメッセージを取得してタグを削除
    $na_d = isset($_GET["na"]) ? htmlspecialchars($_GET["na"]) : null;
    $me_d = isset($_GET["me"]) ? htmlspecialchars($_GET["me"]) : null;


//IPアドレス取得
$ip = getenv("REMOTE_ADDR");

//スレッドグループ番号(gu)に一致するレコードを表示
$re = mysqli_query($s, "SELECT sure FROM tbj0 WHERE guru = $gu_d");
$kekka = mysqlI_fetch_array($re);

//スレッド内容の表示文字列$sure_comを作成
$sure_com = "「".$gu_d." ".$kekka[0]."」";

//スレッド表示のタイトルなどの書きだし
print <<<eot2
    <html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>SQLカフェ $sure_com スレッド</title>
    </head>
    <body gbcolor="darkgray">
        <font size="7" color="indigo">
            $sure_com スレッド!
        </font>
        <br><br>
        <font size="5">
            $sure_com のメッセージ
        </font>
        <br>
eot2;

//名前($na_d)が入力されていればtbj1にレコード挿入

if($na_d != "") {
    mysqli_query($s, "INSERT INTO tbj1 VALUES (0,'$na_d', '$me_d',now(),$gu_d,'$ip')") or die("レコード挿入に失敗しました");
}

//水平線表示
print "<hr>";

//日次の順にレスデータを表示
$re = mysqli_query($s, "SELECT * FROM tbj1 WHERE guru = $gu_d ORDER BY niti") or die("レスデータ表示に失敗しました");

$i = 1;
while($kekka = mysqlI_fetch_array($re)) {
    print "$i($kekka[0]):<u>$kekka[1]</u>:$kekka[3] <br>";
    print nl2br($kekka[2]);
    print "<br><br>";
    $i++;
}

//データベース切断

mysqli_close($s);

print <<<eot3
        <hr>
        <font size="5">
            $sure_com にメッセージを書くときはここにどうぞ
        </font>
        <form method="get" action="keizi.php">
            名前 <input type="text" name="na"><br>
            メッセージ<br>
            <textarea name="me" rows="10" cols="70"></textarea>
            <br>
            <input type="hidden" name="gu" value="$gu_d">
            <input type="submit" value="送信">
        </form>
        <hr>
        <a href="keizi_top.php">スレッド一覧に戻る</a>
    </body>
    </html>
eot3;


//gu_dに数字以外も、数字も含まれていないときの処理
} else {
    print "スレッドを選択してください。<br>";
    print "<a href='keizi_top'>ここをクリックしてスレッド一覧に戻ってください</a>";
}
?>