<?php
function receipt() {

    $session = Session::getInstance();
    $user = $session->get('user');

    $street = isset($_POST['street']) ? $_POST['street'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $paymentMethod = isset($_POST['payment-method']) ? $_POST['payment-method'] : '';
    $expedited = isset($_POST['expedited']) ? 'Yes' : 'No';
    $cart = isset($_POST['cart']) ? $_POST['cart'] : [];
    $total = isset($_POST['total']) ? $_POST['total'] : 0;

    ?>

    <link rel="stylesheet" type="text/css" href="/src/css/receipt.css">

    <section class="receipt-section">
        <div class="receipt-container">
            <h1>Thank you for your purchase, <?= htmlspecialchars($user->name) ?>! </h1>
            <h4>Total amount paid: <?= htmlspecialchars($total) ?>€ </h4>
            <h4>Payment method used: <?= htmlspecialchars($paymentMethod) ?> </h4>
            <h4>Number of items bought: <?= count($cart) ?> </h4>
            <h4>Will be delivered to <?= htmlspecialchars($street) ?>, in <?= htmlspecialchars($country) ?> </h4>

            <h3>Purchased Items:</h3>
            <ul>
                <?php foreach ($cart as $item) : ?>
                    <li>
                        Item: <?= htmlspecialchars($item['name']) ?><br>
                        Condition: <?= htmlspecialchars($item['condition']) ?><br>
                        Price: <?= htmlspecialchars($item['price']) ?>€
                    </li>
                <?php endforeach; ?>
            </ul>

            <a href="../pages/index.php" class="btn-home">Go back home</a>
        </div>
    </section>

    <?php
}
?>
