<?php
include __DIR__. '/mod/initialization.php';
header('Content-Type: application/json');

// 再檢查一次登入跟購物車狀態
if(! isset($_SESSION['user']) or ! isset($_SESSION['cart'])){
    header('Location: product-list.php');
    exit;
}

$_SESSION['total_price'] = isset($_GET['total_price']) ? intval($_GET['total_price']) : 0;

//隨機訂單編號
date_default_timezone_set('Asia/Taipei');
$randID = date("YmdHis").rand(1000000000,9999999999);

//order_main
$sql = "INSERT INTO `order_main`(`order_id`,`member_sid`,`total_price`,`created_at`)
        VALUES(?,?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt -> execute([
    $randID,
    $_SESSION['user']['sid'],
    $_SESSION['total_price'],
]);

//order_detail
$d_sql = "INSERT INTO `order_detail`(`order_main_id`,`product_sid`,`qty`)
        VALUES(?, ?, ?)";

$d_stmt = $pdo->prepare($d_sql);
foreach($_SESSION['cart'] as $sid=>$qty){
    $d_stmt -> execute([
        $randID,
        $sid,
        $qty,
    ]);
}

//移除購物車
unset($_SESSION['cart']);
unset($_SESSION['total_price']);


echo json_encode($_SESSION, JSON_UNESCAPED_UNICODE);