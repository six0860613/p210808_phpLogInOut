<?php include __DIR__ . '/mod/initialization.php' ?>
<?php $title = "訂單查詢"; ?>

<?php

//選取目前登入使用者的全部訂單
if (!isset($_SESSION['user']['sid'])) {
    header('Location: index_.php');
} else {
    $member_sid = $_SESSION['user']['sid'];
    unset($_SESSION['order_id']);
}

$sql = sprintf(
    "SELECT * FROM `order_main` WHERE `member_sid` = %d ORDER BY sid DESC",
    $member_sid
);

$rows = $pdo->query($sql)
    ->fetchAll();

?>

<?php include __DIR__ . '/mod/html-header.php' ?>
<?php include __DIR__ . '/mod/html-navbar.php' ?>

<style>
    .checkBTN,
    .checkBTN_modal {
        background-color: lightskyblue;
        color: white;
    }

    table tbody .fa-trash-alt {
        color: white;
    }

    .course_img {
        width: 200px;
    }

    .qty {
        display: inline-block;
        width: auto;
    }
    .modal-dialog{
        margin: 1.75rem auto;
    }
    @media (min-width: 576px){
        .modal-dialog {
            max-width: 1000px;
        }
    }
    .modal-content{
        width: 1000px;
    }
    
</style>

<div class="container-fluid">
    <!-- 商品陳列 -->
    <div class="row">
        <div class="col-12 my-4">
            <h3>訂單查詢</h3>
        </div>
        <div class="col-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">sid</th>
                        <th scope="col">訂單編號</th>
                        <th scope="col">訂單總金額</th>
                        <th scope="col">建立時間</th>
                        <th scope="col">查看詳情(彈窗)</th>
                        <th scope="col">查看詳情(跳轉)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr class="cartItem" data-orderid="<?= $r['order_id'] ?>">
                            <td><?= $r['sid'] ?></td>
                            <td class="order_id"><?= $r['order_id'] ?></td>
                            <td><?= '$ ' . $r['total_price'] ?></td>
                            <td><?= $r['created_at'] ?></td>
                            <td>
                                <button type="button" class="btn checkBTN_modal" data-toggle="modal" data-target="#checkModal">
                                    <i class="fas fa-clipboard-list"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn checkBTN">
                                    <i class="fas fa-clipboard-list"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 查看詳情(彈窗) -->
    <!-- Modal -->
    <div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="checkModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9 iframe1"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/mod/html-script.php'; ?>
<script>
    // 商品查看詳情功能
    const modal = $('#checkModal');
    let order_id = 0

    //跳轉
    $('.checkBTN').on('click', function(event) {
        order_id = event.target.closest('tr').dataset.orderid;
        // console.log(order_id);
        location.href = `myorder_detail.php?order_id=${order_id}`;
    });
    //彈窗
    $('.checkBTN_modal').on('click', function(event) {
        order_id = event.target.closest('tr').dataset.orderid;
        // console.log(order_id);
        modal.find('.modal-title').html('訂單編號： ' + order_id);
        modal.find('.iframe1').html(`<iframe class="embed-responsive-item" src="myorder_detail_w.php?order_id=${order_id}" allowfullscreen></iframe>`);
    });
</script>
<?php include __DIR__ . '/mod/html-foot.php'; ?>