<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if($_SERVER["REQUEST_METHOD"] = "POST")
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
// データベースの接続処理が終わり//

if(insert($pdo,$data))
{
    $_SESSION["success"] ="新商品追加が完了しました";
    header("Location: zaiko_ichiran.php");
    exit;
}


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
?>