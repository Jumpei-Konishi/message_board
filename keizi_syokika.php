<?php

//データベース情報等の読み込み

require_once("data/db_info.php");

//データベースへ接続、データベース選択
$s = mysqli_connect($SERV,$USER,$PASS) or die("失敗しました");
mysqli_select_db($s, $DBMM);


mysqli_query($s, 'DELETE FROM tbj0');
mysqli_query($s, 'DELETE FROM tbj1');
mysqli_query($s, 'ALTER TABLE tbj0 AUTO_INCREMENT=0');
mysqli_query($s, 'ALTER TABLE tbj1 AUTO_INCREMENT=0');

print "SQLカフェのテーブルを初期化しました。";
print "スレッドを選択してください。<br>";
print "<a href='keizi_top.php'>ここをクリックしてスレッド一覧に戻ってください</a>";
//データベース切断
mysqli_close($s);
?>
