<?php
  require_once('includes/load.php');
  
  
  if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
  } else {
    echo "Order not found.";
    exit;
  }
?>

<?php include_once('layouts/header.php'); ?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Payment Successful!</h2>
        <hr class="mx-auto">
        <p>Your order has been successfully paid. Order ID: <?php echo $order['order_id']; ?></p>
        <p>Thank you for your purchase!</p>
        <a href="account.php" class="btn btn-primary">Go to Account</a>
    </div>
</section>

<?php include_once('layouts/footer.php'); ?>
