<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
	unset($_POST["decision"]);
    
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

// $minPrice = getMinValue($pdo)["minprice"];
$maxPrice = getMaxValue($pdo)["maxprice"];

// function getMinValue($pdo)
// {
// 	$sql = "SELECT MIN(price) as minprice FROM books";
// 	$statement = $pdo->query($sql);
// 	$result = $statement->fetch(PDO::FETCH_ASSOC);
// 	return $result;
// }

function getMaxValue($pdo)
{
	$sql = "SELECT MAX(price) as maxprice FROM books";
	$statement = $pdo->query($sql);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	return $result;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
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

	<form action="search_result.php" method="post"> 
		<div id="pagebody">
			<!-- エラーメッセージ -->
			<div id="error">
			<?php		
				
			?>
			</div>
			<!-- エラーメッセージ終わり -->
			
			<!-- フォームセクション -->
			<div id="center">
				<table>
					<thead>
						<tr>
							<th>キーワード</th>
							<th>発売年代</th>
							<th>金額</th>
							<th>在庫数</th>
						</tr>
					</thead>
					<tr>
						<td><input type='text' name='title' size='20' maxlength='20' placeholder="<- キーワードを入力 ->"></td>
						<td>
							<select id="releaseSearch"  name="release">
								<option value=""><- 発売年代を入力 -></option>
								<?php foreach (range(1970,date("Y") - 1,10) as $year):?>
								<option value="<?=$year?>"><?= $year?>年代</option>
								<?php endforeach ?>
							</select>
						<td>
							<select id="itemPriceSearch" name="itemPrice">
								<?php
									$range1 = range(400,900,100);
									$range2 = range(1000,$maxPrice,1000);
									$rangePrice = array_merge($range1,$range2);
								?>
								<option value=""><- 金額を選択 -></option>
								<?php foreach ($rangePrice as $price):?>
								<option value="<?=$price?>"><?=$price?>円代</option>		
								<?php endforeach?>
							</select>
						</td>
						<td>
							<select id="stockSearch" name="stock">
								<option value=""><- 在庫数 -></option>
								<?php foreach (range(10,40,10) as $stock):?>
								<option value="<?=$stock?>"><?= $stock?>冊未満</option>
								<?php endforeach ?>
								<option value="50">50冊以上</option>
							</select>
						</td>
					</tr>
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
			</div>
			<!-- フォームセクション終わり -->
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>