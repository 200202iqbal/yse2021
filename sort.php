<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

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

if(isset($_POST["sortSales"]))
{
	if($_POST["sortSales"] == "ASC")
	{
		$_POST["sortSalesDate"] = sortDataSales($pdo,"ASC");
		include("zaiko_ichiran.php");
		exit;
	}
	
	if($_POST["sortSales"] == "DESC")
	{
		$_POST["sortSalesDate"] = sortDataSales($pdo,"DESC");
		include("zaiko_ichiran.php");
		exit;
	}
}
if(isset($_POST["sortPrice"]))
{
	if($_POST["sortPrice"] == "ASC")
	{
		$_POST["sortItemPrice"] = sortPrices($pdo,"ASC");
		include("zaiko_ichiran.php");
		exit;
	}

	if($_POST["sortPrice"] == "DESC")
	{
		$_POST["sortItemPrice"] = sortPrices($pdo,"DESC");
		include("zaiko_ichiran.php");
		exit;
	}
}

if(isset($_POST["sortStock"]))
{
	if($_POST["sortStock"] == "ASC")
	{
		$_POST["sortStocks"] = sortStock($pdo,"ASC");
		include("zaiko_ichiran.php");
		exit;
	}

	if($_POST["sortStock"] == "DESC")
	{
		$_POST["sortStocks"] = sortStock($pdo,"DESC");
		include("zaiko_ichiran.php");
		exit;
	}
}

header("Location: zaiko_ichiran.php");
exit;

function sortDataSales($pdo,$code)
{
	$sql = "SELECT * FROM books ORDER BY salesDate {$code}";
	$statement = $pdo->query($sql);
	return $statement;
}

function sortPrices($pdo,$code)
{
	$sql = "SELECT * FROM books ORDER BY price {$code}";
	$statement = $pdo->query($sql);
	return $statement;
}

function sortStock($pdo,$code)
{
	$sql = "SELECT * FROM books ORDER BY stock {$code}";
	$statement = $pdo->query($sql);
	return $statement;
}
?>