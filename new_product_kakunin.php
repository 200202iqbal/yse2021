<?php
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
if($_SERVER["REQUEST_METHOD"] = "POST" && $_POST["decision"] == 1)
{
    $data = check($_POST);
    $errors = validate($data);
    	if(!empty($errors))
    	{
        $_SESSION["errors"] = $errors;
        header("Location: new_product.php");
        exit;
   		}
}

function check($data)
{
    if(empty($data)) return;
    foreach ($data as $posts => $post)
    {
        $data[$posts] = htmlspecialchars($post, ENT_QUOTES);
    }
    return $data;
}

function validate($data)
{
    $errors = [];
    if(!is_numeric($data["itemPrice"])) $errors["itemPrice"] = "金額(円) : 数値以外が入力されています";
    if((int)$data["itemPrice"] < 0) $errors["itemPrice"] = "金額(円) : 0以上入力してください。";
    
    if(!is_numeric($data["stock"])) $errors["stock"] = "在庫数 : 数値以外が入力されています";
    if((int)$data["stock"]<0)$errors["stock"] = "在庫数 : 0以上入力してください。";
    
    if(!is_numeric($data["in"])) $errors["in"] = "入荷数 : 数値以外が入力されています";
    if((int)$data["in"]<0)$errors["in"] = "入荷数 : 0以上入力してください。";
    
    if(!is_numeric($data["isbn"])) $errors["isbn"] = "isbn : 数値以外が入力されています";
    return $errors;
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

<<<<<<< Updated upstream
function insert($pdo,$data)
{
    //isbn番号を自動で作る
    // $today = date("ymd");
    // $data["isbn"] = "9784253".$today;
    // $data["stock"] += $data["in"];

    $sql = "INSERT INTO books (title,author,salesDate,isbn,price,stock,deleteFlag)
            VALUES (:title,:author,:salesDate,:isbn,:price,:stock)";
    $statement = $pdo->prepare($sql);
    $item = $statement->execute([
        ":title" => $data["title"],
        ":author" => $data["author"],
        ":salesDate" => $data["salesDate"],
        ":isbn" => $data["isbn"],
        ":price" => $data["itemPrice"],
        ":stock" => $data["stock"],
		":deleteFlag" => 0
    ]);
    return $item;
}

if($_SERVER["REQUEST_METHOD"] = "POST"&& isset($_POST["tsuika"]) == "ok")
{
=======
<<<<<<< Updated upstream

=======
function insert($pdo,$data)
{
    //isbn番号を自動で作る
    // $today = date("ymd");
    // $data["isbn"] = "9784253".$today;
    // $data["stock"] += $data["in"];

    $sql = "INSERT INTO books (id,title,author,salesDate,isbn,price,stock,deleteFlag)
            VALUES (:id,:title,:author,:salesDate,:isbn,:price,:stock,:deleteFlag)";
    $statement = $pdo->prepare($sql);
    $item = $statement->execute([
		":id" => $data["id"],
        ":title" => $data["title"],
        ":author" => $data["author"],
        ":salesDate" => $data["salesDate"],
        ":isbn" => $data["isbn"],
        ":price" => $data["itemPrice"],
        ":stock" => $data["stock"],
		":deleteFlag" => 0
    ]);
    return $item;
}

if($_SERVER["REQUEST_METHOD"] = "POST" && $_POST["decision"] == 2)
{
	$data = $_POST;
>>>>>>> Stashed changes
	if(insert($pdo,$data))
	{
		$_SESSION["success"] ="新商品追加が完了しました";
		header("Location: zaiko_ichiran.php");
		exit;
	}	
<<<<<<< Updated upstream
}
var_dump($_POST);
=======

}
>>>>>>> Stashed changes
>>>>>>> Stashed changes
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
	<form action="new_product_kakunin.php" formmetho="post">
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
						</tr>
					</thead>
					<tbody>
						<tr>
<<<<<<< Updated upstream
							<td><?=$data["id"] ?></td>
							<td><?=$data["title"] ?></td>
							<td><?=$data["author"] ?></td>
							<td><?=$data["salesDate"] ?></td>
							<td><?=$data["isbn"] ?></td>
							<td><?=$data["itemPrice"] ?></td>
							<td><?=$data["stock"] ?></td>	
=======
<<<<<<< Updated upstream
							
							<td><?php echo	/* ㉟ ㉞で取得した書籍情報からtitleを表示する。 */$selectedBook["title"];?></td>
							<td><?php echo	/* ㊱ ㉞で取得した書籍情報からstockを表示する。 */$selectedBook["stock"];?></td>
							<td><?php echo	/* ㊱ POSTの「stock」に設定されている値を㉜の変数を使用して呼び出す。 */$stock = $_POST["stock"][$count]?></td>
=======
							<td><?=$data["id"] ?></td>
							<input type="hidden" name="id" value="<?php echo $data["id"]; ?>">
							<td><?=$data["title"] ?></td>
							<input type="hidden" name="title" value="<?php echo $data["title"]; ?>">
							<td><?=$data["author"] ?></td>
							<input type="hidden" name="author" value="<?php echo $data["author"]; ?>">
							<td><?=$data["salesDate"] ?></td>
							<input type="hidden" name="salesDate" value="<?php echo $data["salesDate"]; ?>">
							<td><?=$data["isbn"] ?></td>
							<input type="hidden" name="isbn" value="<?php echo $data["isbn"]; ?>">
							<td><?=$data["itemPrice"] ?></td>
							<input type="hidden" name="itemPrice" value="<?php echo $data["itemPrice"]; ?>">
							<td><?=$data["stock"] ?></td>
							<input type="hidden" name="stock" value="<?php echo $data["stock"]; ?>">	
>>>>>>> Stashed changes
>>>>>>> Stashed changes
						</tr>
					</tbody>
				</table>
<<<<<<< Updated upstream
				<input type="hidden" name="" value="<?php echo $data["id"]; ?>">
=======
<<<<<<< Updated upstream
=======
				
>>>>>>> Stashed changes
>>>>>>> Stashed changes
				<div id="kakunin">
					<p>
						上記の書籍を登録します。<br>
						よろしいですか？
					</p>
<<<<<<< Updated upstream
					<button type="submit" id="message" formmethod="POST" name="tsuika" value="ok">はい</button>
=======
<<<<<<< Updated upstream
					<button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
=======
					<button type="submit" id="message" formmethod="POST" name="decision" value=2>はい</button>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
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