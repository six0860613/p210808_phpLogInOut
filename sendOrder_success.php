<?php include __DIR__ . '/mod/initialization.php'; ?>
<?php $title = "首頁"; ?>

<?php include __DIR__ . '/mod/html-header.php'; ?>
<?php include __DIR__ . '/mod/html-navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12 my-5">
            <h1>訂單已成功送出</h1>
        </div>
        <div class="col-2">
            <h5>
                <a href="product-list.php">繼續選購</a>
            </h5>
        </div>
        <div class="col-2">
            <h5>
                <a href="myorder.php">查看訂單</a>
            </h5>
        </div>
    </div>
</div>

<?php include __DIR__ . '/mod/html-script.php'; ?>
<?php include __DIR__ . '/mod/html-foot.php'; ?>