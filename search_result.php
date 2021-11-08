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

if($_POST["decision"] == "fromProductSearch" && !empty($_POST["keyword"]) || !empty($_POST["release"]) || !empty($_POST["itemPrice"] )|| !empty($_POST["stock"]))
{
	$keyword = $_POST["keyword"];
	$release = $_POST["release"];
	$price = $_POST["itemPrice"];
	$stock = $_POST["stock"];
	
	$books = searchData($pdo,$keyword,$release,$price,$stock);
	
}
else
{
	$_SESSION["success"] = "商品検索を入力してください";
	header("Location: product_search.php");
	exit;
}

function searchData($pdo,$keyword,$release,$price,$stock)
{
	
	$rangeRelease = strval((int)($release)+9);
	$rangePrice = rangePrice($price);

	$sql = "SELECT * FROM books";

	// 0000
	if(empty($keyword) && empty($release) && empty($price) && empty($stock))
	{
		$_SESSION["success"] = "商品検索を入力してください";
		header("Location: product_search.php");
		exit;
	}

	//1000
	if(!empty($keyword) && empty($release) && empty($price) && empty($stock)) $sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%'";

	//0100
	if(empty($keyword) && !empty($release) && empty($price) && empty($stock)) $sql .= " WHERE salesDate BETWEEN {$release} AND {$rangeRelease}";

	//1100
	if(!empty($keyword) && !empty($release) && empty($price) && empty($stock)) $sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND salesDate BETWEEN {$release} AND {$rangeRelease}";

	//0010
	if(empty($keyword) && empty($release) && !empty($price) && empty($stock)) $sql .= " WHERE price BETWEEN {$price} AND {$rangePrice}";

	//1010
	if(!empty($keyword) && empty($release) && !empty($price) && empty($stock)) $sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND price BETWEEN {$price} AND {$rangePrice}";

	//0110
	if(empty($keyword) && !empty($release) && !empty($price) && empty($stock)) $sql .= " WHERE salesDate BETWEEN {$release} AND {$rangeRelease} AND price BETWEEN {$price} AND {$rangePrice}";	

	//1110
	if(!empty($keyword) && !empty($release) && !empty($price) && empty($stock)) $sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND salesDate BETWEEN {$release} AND {$rangeRelease} AND price BETWEEN {$price} AND {$rangePrice}";
	
	//0001
	if(empty($keyword) && empty($release) && empty($price) && !empty($stock))
	{
		$stockB = (int)$stock;
		$stockA = $stockB;

		if($stock < 50) 
		{
			$stockA -= 10;
			$stockB -= 1;
			
			$sql .= " WHERE stock BETWEEN {$stockA} AND {$stockB}";
		}
		if($stock>=50)
		{
			$sql .= " WHERE stock >= {$stock}";
		}
	}

	//1001
	if(!empty($keyword) && empty($release) && empty($price) && !empty($stock))
	{
		$stockB = (int)$stock;
		$stockA = $stockB;
		
		if($stock < 50)
		{
			$stockA -= 10;
			$stockB -= 1;
			$sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND stock BETWEEN {$stockA} AND {$stockB}";
		}
		if($stock >= 50)
		{
			$sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND stock >= {$stock}";
		}
	}

	//0101
	if(empty($keyword) && !empty($release) && empty($price) && !empty($stock))
	{
		$stockB = (int)$stock;
		$stockA = $stockB;

		if($stock < 50) 
		{
			$stockA -= 10;
			$stockB -= 1;
			
			$sql .= " WHERE salesDate BETWEEN {$release} AND {$rangeRelease} AND stock BETWEEN {$stockA} AND {$stockB}";
		}
		if($stock>=50)
		{
			$sql .= " WHERE salesDate BETWEEN {$release} AND {$rangeRelease} AND stock >= {$stock}";
		}
	}

	//1101
	if(!empty($keyword) && !empty($release) && empty($price) && !empty($stock))
	{	
		$stockB = (int)$stock;
		$stockA = $stockB;
		
		if($stock < 50)
		{
			$stockA -= 10;
			$stockB -= 1;
			$sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND salesDate BETWEEN {$release} AND {$rangeRelease} AND stock BETWEEN {$stockA} AND {$stockB}";
		}
		if($stock >= 50)
		{
			$sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND salesDate BETWEEN {$release} AND {$rangeRelease} AND stock >= {$stock}";
		}
	}

	//0011
	if(empty($keyword) && empty($release) && !empty($price) && !empty($stock))
	{
		$stockB = (int)$stock;
		$stockA = $stockB;
		
		if($stock < 50)
		{
			$stockA -= 10;
			$stockB -= 1;
			$sql .= " WHERE price BETWEEN {$price} AND {$rangePrice} AND stock BETWEEN {$stockA} AND {$stockB}";
		}
		if($stock >= 50)
		{
			$sql .= " WHERE price BETWEEN {$price} AND {$rangePrice} AND stock >= {$stock}";
		}
	}

	//1011
	if(!empty($keyword) && empty($release) && !empty($price) && !empty($stock))
	{
		$stockB = (int)$stock;
		$stockA = $stockB;
		
		if($stock < 50)
		{
			$stockA -= 10;
			$stockB -= 1;
			$sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND price BETWEEN {$price} AND {$rangePrice} AND stock BETWEEN {$stockA} AND {$stockB}";
		}
		if($stock >= 50)
		{
			$sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND price BETWEEN {$price} AND {$rangePrice} AND stock >= {$stock}";
		}
	}

	//0111
	if(empty($keyword) && !empty($release) && !empty($price) && !empty($stock))
	{	
		$stockB = (int)$stock;
		$stockA = $stockB;
		
		if($stock < 50)
		{
			$stockA -= 10;
			$stockB -= 1;
			$sql .= " WHERE salesDate BETWEEN {$release} AND {$rangeRelease} AND price BETWEEN {$price} AND {$rangePrice} AND stock BETWEEN {$stockA} AND {$stockB}";
		}
		if($stock >= 50)
		{
			$sql .= " WHERE salesDate BETWEEN {$release} AND {$rangeRelease} AND price BETWEEN {$price} AND {$rangePrice} AND stock >= {$stock}";
		}
	}

	//1111
	if(!empty($keyword) && !empty($release) && !empty($price) && !empty($stock))
	{	
		$stockB = (int)$stock;
		$stockA = $stockB;
		
		if($stock < 50)
		{
			$stockA -= 10;
			$stockB -= 1;
			$sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND salesDate BETWEEN {$release} AND {$rangeRelease} AND price BETWEEN {$price} AND {$rangePrice} AND stock BETWEEN {$stockA} AND {$stockB}";
		}
		if($stock >= 50)
		{
			$sql .= " WHERE title LIKE '%{$keyword}%' OR author LIKE '%{$keyword}%' AND salesDate BETWEEN {$release} AND {$rangeRelease} AND price BETWEEN {$price} AND {$rangePrice} AND stock >= {$stock}";
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

$countResult = countSizeoResult($books);

function countSizeoResult($books)
{
	return sizeof($books);
}
// if(isset($_POST))
// {
// 	$books = searchData($pdo,$keyword,$release,$price,$stock);
// }


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
			 echo @$_SESSION["success"];	
			 echo "結果　：".$countResult."書籍"; 
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
						<?php foreach ($books as $book):?>
						<tr id='book'>
							<td id='check'><input type='checkbox' name='books[]' value="<?= $book["id"]?>"></td>
							<td id='id'><?=$book["id"]?></td>
							<td id='title'><?=$book["title"]?></td>
							<td id='author'><?=$book["author"]?></td>
							<td id='date'><?=$book["salesDate"]?></td>
							<td id='price'><?=$book["price"]?></td>
							<td id='stock'><?=$book["stock"]?></td>
						
						</tr>
						<?php endforeach?>
					<?php endif?>
				</table>
                <div id="buttonContainer">
                    
                <button type="submit" id="kakutei2" formmethod="POST" name="decision" formaction="nyuka.php" value="1">入荷</button>
                <button type="submit" id="kakutei2" formmethod="POST" name="decision" formaction="syukka.php" value="2">出荷</button>
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