<?
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!isset($_SESSION["user"]) || $_SESSION["login"] == false)
{
    $_SESSION["error2"] = "ログインしてください";
    header("Location: login.php");
    exit;
}
//データベースへ接続し、接続情報を変数に保存する
$dbname = "zaiko2021_yse";
$host = "localhost";
$charset = "UTF8";
$user = "zaiko2021_yse";
$password = "2021zaiko";
$option = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION];

//データベースで使用する文字コードを[UTF8]にする
$dsn = "mysql:dbname={$dbname};host={$host};charset={$charset}";
try{
    $pdo = new PDO($dsn,$user,$password,$option);
	
}catch(PDOException $e)
{
    die($e->getMessage());
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>新商品追加確認</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<div id="header">
		<h1>新商品追加確認</h1>
	</div>
	<form action="nyuka_kakunin.php" method="post" id="test">
		<div id="pagebody">
			<div id="center">
				<table>
					<thead>
						<tr>
                            <th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="isbn">ISBN</th>
							<th id="itemPrice">金額(円)</th>
							<th id="stock">在庫数</th>
							<th id="in">入荷数</th>
						</tr>
					</thead>
					<tbody>
						<?php
						//㉜書籍数をカウントするための変数を宣言し、値を0で初期化する。
						$count = 0;
						//var_dump($_POST["stock"]);
						//㉝POSTの「books」から値を取得し、変数に設定する。
						$ids = $_POST["books"];
						foreach($ids as $id){
							//㉞「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に㉜の処理で取得した値と⑧のDBの接続情報を渡す。
							$selectedBook = getByid($id,$pdo);
							
						?>
						<tr>
							
							<td><?php echo	/* ㉟ ㉞で取得した書籍情報からtitleを表示する。 */$selectedBook["title"];?></td>
							<td><?php echo	/* ㊱ ㉞で取得した書籍情報からstockを表示する。 */$selectedBook["stock"];?></td>
							<td><?php echo	/* ㊱ POSTの「stock」に設定されている値を㉜の変数を使用して呼び出す。 */$stock = $_POST["stock"][$count]?></td>
						</tr>
						<input type="hidden" name="books[]" value="<?php echo /* ㊲ ㉝で取得した値を設定する */$selectedBook["id"]; ?>">
						
						<input type="hidden" name="stock[]" value='<?php echo /* ㊳POSTの「stock」に設定されている値を㉜の変数を使用して設定する。 */$stock;?>'>
						<?php
							//㊴ ㉜で宣言した変数をインクリメントで値を1増やす。
							$count++;
						}
						?>
					</tbody>
				</table>
				<div id="kakunin">
					<p>
						上記の書籍を登録します。<br>
						よろしいですか？
					</p>
					<button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
					<button type="submit" id="message" formaction="new_product.php">いいえ</button>
				</div>
			</div>
		</div>
	</form>
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>