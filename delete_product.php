<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!isset($_SESSION["user"]) || $_SESSION["login"] == false)
{
    $_SESSION["error2"] = "ログインしてください。";
    header("Location: login.php");
    exit;
}

//データベース情報
$dbname = "zaiko2021_yse";
$host = "localhost";
$charset = "UTF8";
$user =  "zaiko2021_yse";
$password = "2021zaiko";
$option = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION];


$dsn = "mysql:dbname={$dbname};host={$host};charset={$charset}";
try
{
	$pdo = new PDO($dsn,$user,$password,$option);
	
}catch(PDOException $e)
{
	die($e->getMessage());
}


if(empty($_POST["books"])){
	
	$_SESSION["success"] = "削除する商品が選択されていません";
	
	header("Location: zaiko_ichiran.php");
}else
{
	unset($_SESSION["success"]);
}


function getId($id,$con)
{
    $id = htmlspecialchars($id);
    $sql = "SELECT * FROM books WHERE id = {$id}";
    $statement = $con->query($sql);
    $items = $statement->fetch(PDO::FETCH_ASSOC);
    return $items;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品削除</title>
    <link rel="stylesheet" href="css/ichiran.css" type="text/css">
</head>
<body>
    <!-- ヘッダ -->
    <div id="header">
		<h1>商品削除</h1>
	</div>

	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
			</ul>
		</nav>
	</div>
    <form action="delete_product.php" method="post"> 
		<div id="pagebody">
			<!-- エラーメッセージ -->
			<div id="error">
			<?php
			
			var_dump($_POST["books"]);
			
			
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
					
					$ids = $_POST["books"];
					//var_dump($_POST["books"]);
    				foreach($ids as $id):
    					
					$selectedBook = getId($id,$pdo);
					
					?>
					
					<tr>
						<td><<?php echo $selectedBook["id"] ;?></td>
						<td><<?php echo $selectedBook["title"] ;?></td>
						<td><input type='text' name='salesDate' size='5' maxlength='11' required></td>
						<td><input type='text' name='itemPrice' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock' size='5' maxlength='11' required></td>
						<td><input type='text' name='in' size='5' maxlength='11' required></td>
						
					</tr>
                    <?php endforeach ?>
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