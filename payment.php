<?php
  $page_title = 'Payment';
  require_once('includes/load.php');
  include_once('layouts/header.php');
?>

<?php 

if (isset($_POST['order_total_price']) && isset($_POST['order_status']) && isset($_POST['order_id'])) {
    $order_total_price = $_POST['order_total_price'];
    $order_status = $_POST['order_status'];
    $order_id = $_POST['order_id']; 
} else {
    
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        
        $order_total_price = $_SESSION['total'];
        $order_status = 'not paid'; 
        
        $order_id = $_SESSION['last_order_id']; 
    } else {
        
        $order_total_price = 0;
        $order_status = "no order";
    }
}
?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Payment</h2>
        <hr class="mx-auto">
    </div>  

    <div class="mx-auto container text-center">
        <?php if ($order_total_price > 0) { ?>
            <p>Total payment: ₱<?php echo number_format($order_total_price, 2); ?></p>
            <form action="payment_processing.php" method="POST">
                <input type="hidden" name="order_total_price" value="<?php echo $order_total_price; ?>" />
                <input type="hidden" name="order_status" value="<?php echo $order_status; ?>" />
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" /> <!-- Ensure order_id is passed -->
                <input class="btn btn-success" value="Pay Now" type="submit" name="pay_now" />
            </form>
        <?php } else if ($order_status == "no order") { ?>
            <p>You don't have an order</p>
            <a href="cart.php" class="btn btn-secondary">Go to Cart</a>
        <?php } else { ?>
            <p>Invalid order status or no order found.</p>
        <?php } ?>
    </div>
</section>

<?php include_once('layouts/footer.php'); ?>
