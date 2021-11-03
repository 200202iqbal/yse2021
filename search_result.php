<?php
session_start();

//チェックセッション
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
try
	{
    	$pdo = new PDO($dsn,$user,$password,$option);
	
	}catch(PDOException $e)
	{
    	die($e->getMessage());
	}

$title = $_POST["title"];
$release = $_POST["release"];
$price = $_POST["itemPrice"];
$stock = $_POST["stock"];
// var_dump(empty($price));


function searchData($pdo,$title,$release,$price,$stock)
{
	
	$rangeRelease = strval((int)($release)+9);
	$rangePrice = rangePrice($price);
	// var_dump($price.PHP_EOL.$rangePrice);

	// var_dump($rangeRelease);
	// var_dump($release);

	$sql = "SELECT * FROM books";
	if(!empty($title)) $sql .= " WHERE title LIKE '%{$title}%'";
	if(!empty($release)) $sql .= " WHERE salesDate BETWEEN {$release} AND {$rangeRelease}";
	if(!empty($price)) $sql .= " WHERE price BETWEEN {$price} AND {$rangePrice}";
	if(!empty($stock))
	{
		$rangeStock = (int)$stock;
		// var_dump($stock.PHP_EOL.$rangeStock);
		if($stock < 50) 
		{
			$rangeStock += 9;
			$sql .= " WHERE stock BETWEEN {$stock} AND {$rangeStock}";
		}
		if($stock>=50)
		{
			$sql .= " WHERE stock >= {$stock}";
		}

	}
	

	$rows = [];
	$statements = $pdo->query($sql);
	while ($row = $statements->fetch(PDO::FETCH_ASSOC))
	{
		$rows[] = $row;
	}
	return $rows;
}
function rangePrice($price)
{
	if((int)$price < 1000) $rangePrice = (int)$price + 100;
	if((int)$price >=1000) $rangePrice = (int)$price + 1000;
	return $rangePrice;
}

if(isset($_POST))
{
	$books = searchData($pdo,$title,$release,$price,$stock);
	// var_dump($books);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>商品結果</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>商品結果</h1>
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
				// var_dump($books)
			?>
			</div>
			<!-- エラーメッセージ終わり -->
			
			<!-- フォームセクション -->
			<div id="center">
				<table>
					<thead>
						<tr>
                            <th id="check"></th>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
                            <th id="itemPrice">金額</th>
                            <th id="stock">在庫数</th>
						</tr>
					</thead>
					<?php if ($books):?>
						<?foreach ($books as $book):?>
						<tr id='book'>
							<td id='check'><input type='checkbox' name='books[]' value=".$books["id"]."></td>
							<td id='id'><?=$book["id"]?></td>
							<td id='title'><?=$book["title"]?></td>
							<td id='author'><?=$book["author"]?></td>
							<td id='date'><?=$book["salesDate"]?></td>
							<td id='price'><?=$book["price"]?>"</td>
							<td id='stock'><?=$book["stock"]?></td>
						
						</tr>
						<?endforeach?>
					<?endif?>
				</table>
                <div id="buttonContainer">
                    
                <button type="submit" id="kakutei2" formmethod="POST" name="decision" formaction="#" value="1">入荷</button>
                <button type="submit" id="kakutei2" formmethod="POST" name="decision" formaction="#" value="2">出荷</button>
                </div>
				
			</div>
			<!-- フォームセクション終わり -->
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>