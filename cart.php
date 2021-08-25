<?php include __DIR__ . '/mod/initialization.php'; ?>
<?php $title = "商品"; ?>

<?php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; //TODO: 要多寫一個跳alert跟跳轉的JS
} else {
    $get_sid = array_keys($_SESSION['cart']); //取得cart的key(值為sid)
}

$rows = [];
$p_detail = [];

if (!empty($get_sid)) {
    // 將array: $get_sid轉成字串格式 1,2,3,...
    $sidString = implode(',', $get_sid);
    $sql = sprintf(
        "SELECT `course`.*, `member`.`_name`
        FROM `course` 
        LEFT JOIN `member` 
        ON `course`.`teacher_sid` = `member`.`sid` 
        WHERE `course`.`sid` IN(%s)",
        $sidString
    );
    $rows = $pdo->query($sql)->fetchAll();

    foreach ($rows as $r) {
        $r['qty'] = $_SESSION['cart'][$r['sid']]; //把數量也加進r中
        $p_detail[$r['sid']] = $r; //令sid為key後把對應的r塞進去之後拿來提取
    }
}

?>

<?php include __DIR__ . '/mod/html-header.php' ?>
<?php include __DIR__ . '/mod/html-navbar.php' ?>

<style>

</style>

<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col"><i class="fas fa-shopping-cart fa-3x"></i></div>
    </div>
    <!-- 商品陳列 -->
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col"><i class="fas fa-trash-alt"></i></th>
                        <th scope="col">sid</th>
                        <th scope="col">課程圖片</th>
                        <th scope="col">授課老師</th>
                        <th scope="col">教授語系</th>
                        <th scope="col">課程名稱</th>
                        <th scope="col">單價</th>
                        <th scope="col">數量</th>
                        <th scope="col">小計</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $sid => $qty) :
                        $cart_item = $p_detail[$sid]; //單獨提取符合各自sid的item
                    ?>
                        <tr class="cartItem" data-sid="<?= $sid ?>">
                            <td>
                                <button type="button" class="btn deleteBTN" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                            <td><?= $sid ?></td>
                            <td><img class="course_img" src="imgs/<?= $sid ?>.png" alt=""></td>
                            <td><?= $cart_item['_name'] ?></td>
                            <td><?= $cart_item['course_category'] ?></td>
                            <td><?= $cart_item['course_name'] ?></td>
                            <td class="course_price" data-price="<?= $cart_item['course_price'] ?>">
                                $<?= $cart_item['course_price'] ?>
                            </td>
                            <td>
                                <select class="form-control qty" data-qty="<?= $cart_item['qty'] ?>" onchange="qtyRenew(event)">
                                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td class="subTotal"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <p class="total"></p>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <?php if (!isset($_SESSION['user'])) : ?>
                <span class="noneLogin">結帳前請先登入帳號</span>
                <a href="" class="btn sendOrderBtn text-decoration-none disabled bg-secondary" aria-disabled="true">結帳</a>
            <?php else : ?>
                <a href="#" class="sendOrderBtn text-decoration-none" data-toggle="modal" data-target="#sendModal">結帳</a>
            <?php endif; ?>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">提示</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn modal-del-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendLabel">提示</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn modal-send-btn">送出</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/mod/html-script.php' ?>
<script>
    // 商品刪除功能
    const modal = $('#deleteModal');
    let willDeleteID = 0;
    $('.deleteBTN').on('click', function(event) {
        willDeleteID = event.target.closest('tr').dataset.sid;
        console.log(willDeleteID);
        modal.find('.modal-body').html(`確定刪除編號${willDeleteID}嗎?`);
    });
    modal.find('.modal-del-btn').on('click', function(event) {
        fetch('addToCart.php?sid=' + willDeleteID + '&qty=' + 0).then(r => r.json());
        totalPrice();
        location.href = 'cart.php';
    });

    //商品修改數量
    function qtyRenew(event) {
        let sid = $(event.target).closest('tr').attr('data-sid');
        let qty = $(event.target).val();
        console.log({
            sid,
            qty
        });
        fetch('addToCart.php?sid=' + sid + '&qty=' + qty).then(r => r.json());
        totalPrice();
        location.href = 'cart.php'; //ASK 如何做到不跳轉就更新金額
    }

    function totalPrice() {
        const cartItem = document.querySelectorAll('.cartItem');
        if (!cartItem.length) {
            alert('購物車為空');
            location.href = 'product-list.php';
        }
        let total_price = 0;
        cartItem.forEach(function(tr, key) {

            //在tr中取得course_price這個td的data-price
            const course_price = parseInt($(tr).find('.course_price').attr('data-price'));
            //在tr中取得course_qty這個td的data-qty
            const course_qty = parseInt($(tr).find('.qty').attr('data-qty'));
            //把tr的.qty拉出來給值
            $(tr).find('.qty').val(course_qty);
            // console.log("key:", key, "P:", course_price, "Q:", course_qty);

            let price = course_price * course_qty;
            const subTotal = $(tr).find('.subTotal');
            subTotal.text('$' + price);

            total_price += price;
            // console.log(total_price);
        });
        const total = $('.total');
        total.text('總額: $' + total_price);

        return total_price;
    }
    totalPrice();

    //結帳按鈕
    const sendModal = $('#sendModal');
    $('.sendOrderBtn').on('click', function(event) {
        sendModal.find('.modal-body').html(`要送出訂單嗎?`);
    });
    sendModal.find('.modal-send-btn').on('click', function(event) {
        let send_price = totalPrice();
        console.log(send_price);
        fetch('sendOrder.php?total_price=' + send_price).then(r => r.json());
        location.href = 'sendOrder_success.php';
    });
</script>
<?php include __DIR__ . '/mod/html-foot.php' ?>