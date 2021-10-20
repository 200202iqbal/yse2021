<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if($_SERVER["REQUEST_METHOD"] = "POST")
{
    $data = check($_POST);
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


function insert($pdo,$data)
{
    $sql = "INSERT INTO books (title,author,salesDate,price,stock)
            VALUES (:title,:author,:salesDate,:price,:stock)";
    $statement = $pdo->prepare($sql);
    // $item = $statement->execute([
    //     ":title" =>  
    //     ":author" =>
    //     ":salesData" =>
    //     ":price" =>
    //     ":stock" =>
    // ]);
    // return $item;
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
var_dump($data);
?>