<?php
session_start();

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
				var_dump($_POST)
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
					<tr>
						
					</tr>
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