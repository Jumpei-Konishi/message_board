<?php
//データベース情報等の読み込み
require_once("data/db_info.php");

//データベースへ接続、データベース選択
$s = mysqli_connect($SERV,$USER,$PASS) or die("失敗しました");
mysqli_select_db($s, $DBMM);

//スレッドグループ番号を取得し(gu)$gu_dに代入
$gu_d = $_GET["id"];

//$gu_dに数字以外が含まれていない、正常な値での処理
if(preg_match("/[0-9]/", $gu_d)) {
    //名前とメッセージを取得してタグを削除
    $na_d = isset($_GET["na"]) ? htmlspecialchars($_GET["na"]) : null;
    $me_d = isset($_GET["me"]) ? htmlspecialchars($_GET["me"]) : null;


    //IPアドレス取得
    $ip = getenv("REMOTE_ADDR");

    //スレッドグループ番号(gu)に一致するレコードを表示
    $re = mysqli_query($s, "SELECT sure FROM tbj0 WHERE guru = $gu_d");
    $kekka = mysqli_fetch_assoc($re);
    $sure = $kekka['sure'];
    //スレッド内容の表示文字列$sure_comを作成
    $sure_com = "「".$gu_d." ".$sure."」";

//名前($na_d)が入力されていればtbj1にレコード挿入

    if($na_d != "") {
        mysqli_query($s, "INSERT INTO tbj1 VALUES (0,'$na_d', '$me_d',now(),$gu_d,'$ip')") or die("レコード挿入に失敗しました");
    }

    //日次の順にレスデータを表示
    $re = mysqli_query($s, "SELECT * FROM tbj1 WHERE guru = $gu_d ORDER BY niti") or die("レスデータ表示に失敗しました");
    $list = mysqli_fetch_all($re);
}

//データベース切断

mysqli_close($s);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>SQLカフェ <?php echo $sure_com; ?> スレッド</title>
</head>
<body gbcolor="GREY">
    <font size="7" color="indigo">
        <?php echo $sure_com; ?> スレッド!
    </font>
    <br><br>
    <?php if(preg_match("/[^0-9]/", $gu_d)) : ?>

    <p>不正な値が入力されています</p>
    <a href="keizi_top.php">ここをクリックしてスレッド一覧に戻ってください</a>
    <?php elseif(preg_match("/[0-9]/", $gu_d)) : ?>


    <font size="5">
        <?php echo $sure_com; ?> のメッセージ
    </font>
    <br>
    <?php foreach($list as $value) : ?>
    <?php print $value[0]."(".$value[0]."):<u>".$value[1]."</u>:".$value[3]." <br>".$value[2]."<br><br>" ?>
    <?php endforeach; ?>


    <?php else: ?>
        <p>スレッドを選択してください。</p>
        <a href='keizi_top'>ここをクリックしてスレッド一覧に戻ってください</a>
    <?php endif; ?>
    <hr>
    <font size="5">
        <?php echo $sure_com; ?> にメッセージを書くときはここにどうぞ
    </font>
    <form method="get" action="keizi.php">
        名前 <input type="text" name="na"><br>
        メッセージ<br>
        <textarea name="me" rows="10" cols="70"></textarea>
        <br>
        <input type="hidden" name="id" value="<?php echo $gu_d ?>">
        <input type="submit" value="送信">
    </form>
    <hr>
    <a href="keizi_top.php">スレッド一覧に戻る</a>
</body>
</html>