<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
    $_POST = "";
}
if(!isset($_SESSION["user"]) || $_SESSION["login"] == false)
{
    $_SESSION["error2"] = "ログインしてください。";
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
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>商品検索</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>商品検索</h1>
	</div>

	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
			</ul>
		</nav>
	</div>
	<!-- メニュー終わり -->

	<form action="add.php" method="post"> 
		<div id="pagebody">
			<!-- エラーメッセージ -->
			<div id="error">
			<?php				
			?>
			</div>
			<!-- エラーメッセージ終わり -->

			<!-- 一覧セクション -->
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="title">キーワード</th>
							<th id="release">発売年代</th>
							<th id="itemPrice">金額</th>
							<th id="stock">在庫数</th>
						</tr>
					</thead>
					<tr>
						<td><input type='text' name='title' size='5' maxlength='11' required></td>
						<td><input type='text' name='author' size='5' maxlength='11' required></td>
						<td><input type='text' name='itemPrice' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock' size='5' maxlength='11' required></td>
					</tr>
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
			</div>
			<!-- 一覧セクション終わり -->
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>