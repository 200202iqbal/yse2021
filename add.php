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

if(insert($pdo,$data))
{
    $_SESSION["success"] ="新商品追加が完了しました";
    header("Location: zaiko_ichiran.php");
    exit;
}


function insert($pdo,$data)
{
    $today = date("ymd");
    $data["isbn"] = "9784253".$today;

    $sql = "INSERT INTO books (title,author,salesDate,isbn,price,stock)
            VALUES (:title,:author,:salesDate,:isbn,:price,:stock)";
    $statement = $pdo->prepare($sql);
    $item = $statement->execute([
        ":title" => $data["title"],
        ":author" => $data["author"],
        ":salesDate" => $data["salesDate"],
        ":isbn" => $data["isbn"],
        ":price" => $data["itemPrice"],
        ":stock" => $data["stock"],
    ]);
    return $item;
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
?>