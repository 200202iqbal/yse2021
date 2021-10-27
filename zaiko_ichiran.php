<?php
/* 
【機能】
書籍テーブルより書籍情報を取得し、画面に表示する。
商品をチェックし、ボタンを押すことで入荷、出荷が行える。
ログアウトボタン押下時に、セッション情報を削除しログイン画面に遷移する。

【エラー一覧（エラー表示：発生条件）】
入荷する商品が選択されていません：商品が一つも選択されていない状態で入荷ボタンを押す
出荷する商品が選択されていません：商品が一つも選択されていない状態で出荷ボタンを押す
*/

//①セッションを開始する
session_start();


// ②SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if ( !isset($_SESSION["user"]) ||empty($_SESSION["login"])){
// 	// ③SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION["error2"] = "ログインしてください";
// 	// ④ログイン画面へ遷移する。
	header("Location: login.php");
	exit;
}
$_SESSION["account_name"] = $_SESSION["user"];
//⑤データベースへ接続し、接続情報を変数に保存する
$dbname = "zaiko2021_yse";
$host = "localhost";
$charset = "UTF8";
$user =  "zaiko2021_yse";
$password = "2021zaiko";
$option = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION];

//⑥データベースで使用する文字コードを「UTF8」にする
$dsn = "mysql:dbname={$dbname};host={$host};charset={$charset}";

//⑦書籍テーブルから書籍情報を取得するSQLを実行する。また実行結果を変数に保存する
//データベースをPDOで接続してみる
try
{
	$pdo = new PDO($dsn,$user,$password,$option);
	// echo "SUCCESS";
}catch(PDOException $e)
{
	die($e->getMessage());
}
//SQL
$sql = "SELECT * FROM books";

//SQLを実行する
$statement = $pdo->query($sql);	

function listCount($pdo) {
    $sql = "SELECT count(id) AS count FROM books;";
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    return $row['count'];
}

//Pagination
function paginate($count,$current_page,$limit = 15,$per_count = 10)
{
	$page_count = ceil($count/$limit);
	$page_start = $current_page;
	$page_end = $page_start + $per_count -1;
	if($page_end>$page_count)
	{
		$page_end = $page_count;
		$page_start = $page_end - $per_count + 1;
	}
	if($page_start<0) $page_start = 1;

	$page_prev = ($current_page <=1) ? 1:$current_page -1;
	$page_next = ($current_page<$page_count) ? $current_page+1 :$page_count;

	$pages = range($page_start,$page_end);

	$paginate = compact(
		"page_count",
		"page_start",
		"page_end",
		"page_prev",
		"page_next",
		"pages",
	);
	return $paginate;
}

$current_page = (isset($_GET["page"])) ? $_GET["page"] : 1;

$count = listCount($pdo);
$limit = 15;
$offset = ($current_page - 1) * $limit;

$paginate = paginate($count,$current_page,$limit,5);
extract($paginate);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>書籍一覧</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<div id="header">
		<h1>書籍一覧</h1>
	</div>
	<form action="zaiko_ichiran.php" method="post" id="myform" name="myform">
		<div id="pagebody">
			<!-- エラーメッセージ表示 -->
			<div id="error">
				<?php
				/*
				 * ⑧SESSIONの「success」にメッセージが設定されているかを判定する。
				 * 設定されていた場合はif文の中に入る。
				 */
				
				 if(isset($_SESSION["success"])){
				// // 	//⑨SESSIONの「success」の中身を表示する。
				 	echo "<p>".@$_SESSION["success"]."</p>";
					unset($_SESSION["success"]);
				 	//var_dump($_SESSION["success"]);
				 }
				?>
			</div>
			
			<!-- 左メニュー -->
			<div id="left">
				<p id="ninsyou_ippan">
					<?php
						echo @$_SESSION["account_name"];
					?><br>
					<button type="button" id="logout" onclick="location.href='logout.php'">ログアウト</button>
				</p>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="3" formaction="nyuka.php">入荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="syukka.php">出荷</button>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="5" formaction="new_product.php">新商品追加</button>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="6" formaction="delete_product.php">商品削除</button>
			</div>
			<!-- 中央表示 -->
			<div id="center">

				<!-- 書籍一覧の表示 -->
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
					<tbody>
						<?php
						//⑩SQLの実行結果の変数から1レコードのデータを取り出す。レコードがない場合はループを終了する。
						while($books= $statement->fetch(PDO::FETCH_ASSOC)){
							//⑪extract変数を使用し、1レコードのデータを渡す。
							$book = array(
								"id" => $books["id"],
								"title" => $books["title"],
								"author" => $books["author"],
								"date" => $books["salesDate"],
								"price" => $books["price"],
								"stock" => $books["stock"]
							);
							extract($book);

							echo "<tr id='book'>";
							echo "<td id='check'><input type='checkbox' name='books[]' value=".$books["id"]."></td>";
							echo "<td id='id'>".$id."</td>";
							echo "<td id='title'>".$title."</td>";
							echo "<td id='author'>".$author."</td>";
							echo "<td id='date'>".$date."</td>";
							echo "<td id='price'>".$price."</td>";
							echo "<td id='stock'>".$stock."</td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
	<!-- Paginateコード始まり -->
	<nav aria-label="Page navigation">
		<ul class="pagination">
				<!-- &laquo; is an HTML character code for a "left-angle quote," otherwise known as the symbol «. -->
			<li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
			<li class="page-item"><a href="#" class="page-link">Prev</a></li>

			<?php foreach($pages as $page): ?>
				<?php if ($current_page == $page):?>
					<li class="page-item active"><a href="#" class="page-link"></a></li>
				<?php else: ?>
					<li class="page-item"><a href="#" class="page-link"></a></li>
				<?php endif ?>
			<?php endforeach ?>
			<li class="page-item"><a href="" class="page-link">Next</a></li>
			<li class="page-item"><a href="" class="page-link">&raquo;</a></li>	
		</ul>
	</nav>
	<!-- Paginateコード終わり -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>