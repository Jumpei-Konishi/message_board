<?php

//データベース情報等の読み込み

require_once("data/db_info.php");

//データベースへ接続、データベース選択
$s = mysqli_connect($SERV,$USER,$PASS) or die("失敗しました");
mysqli_select_db($s, $DBMM);


// 検索文字列を取得してタグを削除
if (isset($_GET['se'])) {
    $se_d = htmlspecialchars($_GET["se"]);
} else {
    $se_d = null;
}
//検索のSQL文 テーブルtbj1にtbj0を結合
    $sql = <<<eot2
SELECT tbj1.bang, tbj1.nama, tbj1.mess, tbj0.sure
    FROM tbj1
JOIN tbj0
ON
    tbj1.guru = tbj0.guru
WHERE tbj1.mess LIKE "%$se_d%"
eot2;

//検索文字列を取得してタグを削除
if($se_d != "") {
//検索クエリを実行
    $re = mysqli_query($s, $sql);
    $search_list = mysqli_fetch_all($re);
}
//データベース切断
mysqli_close($s);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>SQLカフェ 検索のページ</title>
</head>
<body bgcolor="orange">
    <font size="5">検索結果はこちらに</font><br>
    みたいスレッドの番号をクリックしてください
    <hr>
    <font size="5">(スレッド一覧)</font>
    <br>
    <?php foreach($search_list as $list) : ?>
        <?php print ($list[0]." : ".$list[1]." : ".$list[2]." ( ".$list[3]." )<br>") ?>
        <?php endforeach; ?>
    <?php if(!$search_list) : ?>
        <p>検索結果はありませんでした。</p>
    <?php endif; ?>
    <hr>
    メッセージに含まれる文字を入力してください！
    <br>
    <form method="get" action="keizi_search.php">
        検索する文字列
        <input type="text" name="se">
        <br>
        <input type="submit" value="検索">
    </form>
    <br>
    <a href="keizi_top.php">
        スレッド一覧に戻る
    </a>
</body>
</html>