<?php
function purchase() {

    $session = Session::getInstance();
    $token = $session->getCsrfToken();
    $user = $session->get('user');

    $cart = isset($_POST['cart']) ? $_POST['cart'] : [];
    $total = isset($_POST['total']) ? $_POST['total'] : 0;

    ?>
    
    <link rel="stylesheet" type="text/css" href="/src/css/purchase.css">

    <section class="purchase-section">
        <div class="purchase-container">
            <h2>Purchase summary</h2>
            <form action="../pages/receiptPage.php" method="POST" class="purchaseForm">
                <div class="form-group">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <!-- street -->
                    <label for="street">Street name & number</label>
                    <input type="text" name="street" id="street" placeholder="Enter your street name & no." required>
                    <!-- country -->
                    <label for="country">Country of delivery</label>
                    <input type="text" name="country" id="country" placeholder="Enter your country" required>
                    <!-- payment method -->
                    <label for="payment-method">Payment Method</label>
                    <select name="payment-method" id="payment-method" required>
                        <option value="" disabled selected>Select your payment method</option>
                        <option value="card">Credit/ Debit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="mbway">MB Way</option>
                    </select>
                    <!-- expedited delivery -->
                    <label for="expedited" class="checkbox-container">
                        Expedited delivery
                        <input type="checkbox" name="expedited" id="expedited">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <h5>Buying as <?= htmlspecialchars($user->name) ?></h5>

                <!-- pass cart to receipt page using POST -->
                <?php foreach ($cart as $index => $item) : ?>
                    <input type="hidden" name="cart[<?= $index ?>][name]" value="<?= htmlspecialchars($item['name']) ?>">
                    <input type="hidden" name="cart[<?= $index ?>][condition]" value="<?= htmlspecialchars($item['condition']) ?>">
                    <input type="hidden" name="cart[<?= $index ?>][price]" value="<?= htmlspecialchars($item['price']) ?>">
                <?php endforeach; ?>
                <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">

                <button type="submit" class="purchase-button">Confirm purchase of <?= htmlspecialchars($total) ?>€</button>
            </form>
        </div>
    </section>

    <?php
}
?>
