<?php

//データベース情報等の読み込み

require_once("data/db_info.php");

//データベースへ接続、データベース選択
$s = mysqli_connect($SERV,$USER,$PASS) or die("失敗しました");
mysqli_select_db($s, $DBMM);


// クライアントのIPアドレス取得
$ip = getenv("REMOTE_ADDR");

// スレッドのタイトル(su)にデータがあれば、tbj0に挿入
if (isset($_GET['su'])) {
    $su_d = htmlspecialchars($_GET["su"]);
} else {
    $su_d = null;
}

if($su_d != "") {
    mysqli_query($s, "INSERT INTO tbj0 (sure,niti,aipi) VALUES ('$su_d',now(),'$ip')");
}

//tbj0の全データ抽出
$re = mysqli_query($s, "SELECT * FROM tbj0");
$list = mysqli_fetch_all($re);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>SQLカフェのページ</title>
</head>
<body bgcolor="lightsteelblue">
    <img src="pic/oya.gif">
    <font size="7" color="indigo">SQLカフェの掲示板だよ～</font>
    <br><br>
    みたいスレッドの番号をクリックしてください
    <hr>
    <font size="5">(スレッド一覧)</font>
    <br>
<?php foreach($list as $item) : ?>
    <a href="keizi.php?id=<?php echo $item[0]; ?>"><?php echo $item[0]." ".$item[1] ?></a>
    <br>
    <?php $item[2] ?>作成<br><br>
<?php endforeach; ?>
<hr>
    <font size="5">
        (スレッド作成)
    </font>
    <br>
        新しくスレッドをつくるときは、ここでどうぞ！
    <br>
    <form method="get" action="keizi_top.php">
        新しく作るスレッドのタイトル
        <input type="text" name="su" size="50">
        <br>
        <input type="submit" value="作成">
    </form>
    <hr>
    <font size="5">
        (メッセージ検索)
    </font>
    <a href="keizi_search.php">検索するときはここをクリック</a>
    <hr>
    <a href="keizi_syokika.php">掲示板のスレッドを削除する</a>
    <hr>
</body>
</html>