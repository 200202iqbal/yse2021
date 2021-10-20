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

$pdo->query();
echo $pdo->lastInsertId();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>新商品追加</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>新商品追加</h1>
	</div>

	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
			</ul>
		</nav>
	</div>

	<form action="add.php" method="post"> 
		<div id="pagebody">
			<!-- エラーメッセージ -->
			<div id="error">
			<?php
			/*
			 * ⑬SESSIONの「error」にメッセージが設定されているかを判定する。
			 * 設定されていた場合はif文の中に入る。
			 */ 
			//var_dump($_POST["books"]);
			
			
			if(isset($_SESSION["error"])){
				//⑭SESSIONの「error」の中身を表示する。
				echo '<p>'.$_SESSION["error"].'</p>';
				$_SESSION["error"]="";
			}
			?>
			</div>
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額(円)</th>
							<th id="stock">在庫数</th>
							<th id="in">入荷数</th>
						</tr>
					</thead>
					<?php 
					// /*
					//  * ⑮POSTの「books」から一つずつ値を取り出し、変数に保存する。
					//  */
					// $ids = $_POST["books"];
					//var_dump($_POST["books"]);
    				//  foreach($ids as $id):
    				// ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。	
					// $selectedBook = getId($id,$pdo);
					
					?>
					<!-- <input type="hidden" value=" <?php //echo	/* ⑰ ⑯の戻り値からidを取り出し、設定する */$selectedBook["id"];?>" name="books[]"> -->
					<tr>
						<td><?php echo $current_id;?></td>
						<td><input type='text' name='title' size='5' maxlength='11' required></td>
						<td><input type='text' name='author' size='5' maxlength='11' required></td>
						<td><input type='text' name='salesDate' size='5' maxlength='11' required></td>
						<td><input type='text' name='itemPrice' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock' size='5' maxlength='11' required></td>
						<td><input type='text' name='in' size='5' maxlength='11' required></td>
					</tr>
					<?php //endforeach  ?>
                
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
			</div>
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>