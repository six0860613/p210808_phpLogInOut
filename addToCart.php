<?php
include __DIR__. '/mod/initialization.php';

if(! isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

//傳入sid和qty
$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
$qty = isset($_GET['qty']) ? intval($_GET['qty']) : 0;


if(! empty($sid)){
    if(! empty($qty)){
        // 新增或修改
        $_SESSION['cart'][$sid] = $qty;
        $_SESSION['success'] = true;
    } else {
        // 移除
        unset($_SESSION['cart'][$sid]);
    }
}
header('Content-Type: application/json');

echo json_encode($_SESSION);