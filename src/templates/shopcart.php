<?php 
require_once(__DIR__ .'/itemPreview.php');
require_once(__DIR__ .'/../database/connection.db.php');

function shopcart() {
    
    $session = Session::getInstance();
    $token = $session->getCsrfToken();
    $user = $session->get('user');
    $userId = $user->id ?? "";

    $db = connectToDatabase();
    $items = Post::getShoppingCart($db, $userId);
    $total = 0;
    
    ?>
    <link rel="stylesheet" type="text/css" href="/src/css/shopcart.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400&display=swap" rel="stylesheet">
    <h1>Shopping Cart</h1>
    <div class="container">
        <div class="itempreview">
            <?php
                if(count($items) === 0) {?>
                    <h4>There are no items in your cart</h4>
            <?php } else {
                foreach($items as $post) {
                    $total += $post->price;
                    itemPreview($post, false, true);
                }  
            }      
            ?>
        </div>
        <!-- payment confirmation -->
        <div class="paymentbox">
            <p>Number of items: <?= count($items) ?></p> 
            <p>Items value: <?= $total?>€</p> 
            <p>Delivery: free</p>
            <p class="price"><?= $total?>€</p>
            <form action="../pages/purchasePage.php" method="POST" id="cartForm">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">
                <?php foreach ($items as $index => $post) { ?>
                    <input type="hidden" name="cart[<?= $index ?>][name]" value="<?= htmlspecialchars($post->name) ?>">
                    <input type="hidden" name="cart[<?= $index ?>][condition]" value="<?= htmlspecialchars($post->condition) ?>">
                    <input type="hidden" name="cart[<?= $index ?>][price]" value="<?= htmlspecialchars($post->price) ?>">
                <?php } ?>
                <button type="submit" class="paymentbutton">
                    <p class="payment">Payment</p>
                </button>
            </form>
        </div>
    </div>
<?php }
?>
