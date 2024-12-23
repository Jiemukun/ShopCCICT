<?php
  require_once('includes/load.php');

  
  if (isset($_POST['order_total_price']) && isset($_POST['order_status']) && isset($_POST['order_id'])) {
    $order_total_price = $_POST['order_total_price'];
    $order_status = $_POST['order_status'];
    $order_id = $_POST['order_id'];

    //payment proces
    $payment_successful = true; 

    if ($payment_successful) {
        
        $stmt = $db->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
        $new_status = 'paid';  
        $stmt->bind_param("si", $new_status, $order_id);  
        $stmt->execute();
        
        
        if ($stmt->affected_rows > 0) {
            
            header('Location: payment_success.php?order_id=' . $order_id);
            exit;
        } else {
            
            echo "Error updating order status.";
        }
    } else {
        
        echo "Payment failed. Please try again.";
    }
  } else {
    
    echo "Invalid order details.";
  }
?>
