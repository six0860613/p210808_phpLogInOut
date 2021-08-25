<?php include __DIR__ . '/mod/initialization.php' ?>
<?php $title = "訂單詳情"; ?>

<?php
$order_id = isset($_GET['order_id']) ? strval($_GET['order_id']) : 0;
$d_sql = sprintf(
    "SELECT `order_detail`.*, `course`.*, `member`.`_name` 
    FROM `order_detail` 
    LEFT JOIN `course` 
    ON `order_detail`.`product_sid` = `course`.`sid` 
    LEFT JOIN `member` 
    ON `course`.`teacher_sid` = `member`.`sid`
    WHERE `order_main_id` = %s",
    $order_id
);

$d_rows = $pdo->query($d_sql)->fetchAll();


?>

<?php include __DIR__ . '/mod/html-header.php' ?>

<style>

</style>

<div class="container-fluid">
    <!-- 商品陳列 -->
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">課程ID</th>
                        <th scope="col">課程圖片</th>
                        <th scope="col">授課老師</th>
                        <th scope="col">教授語系</th>
                        <th scope="col">課程名稱</th>
                        <th scope="col">單價</th>
                        <th scope="col">購買數量</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($d_rows as $dr) : ?>
                        <tr class="cartItem" data-sid="<?= $dr['sid'] ?>">
                            <td><?= $dr['product_sid'] ?></td>
                            <td><img class="course_img" src="imgs/<?= $dr['product_sid'] ?>.png" alt=""></td>
                            <td><?= $dr['_name'] ?></td>
                            <td><?= $dr['course_category'] ?></td>
                            <td><?= $dr['course_name'] ?></td>
                            <td>$<?= $dr['course_price'] ?></td>
                            <td><?= $dr['qty'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include __DIR__ . '/mod/html-script.php'; ?>
<script>

</script>
<?php include __DIR__ . '/mod/html-foot.php'; ?>