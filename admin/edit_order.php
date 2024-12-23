<?php
  $page_title = 'Admin';
require_once('../includes/load.php');  
?>
<?php include_once('../admin_layouts/admin_header.php'); 
?>

<?php 
if (!isset($_SESSION['admin_logged_in'])){
  header('location: login.php');
  exit;
}

include_once('session.php');
?>
<?php

if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];
$stmt = $db->prepare("SELECT * FROM orders WHERE order_id=?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$orders = $stmt->get_result();
}elseif(isset($_POST['edit_btn'])){
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];
    
    $stmt = $db->prepare("UPDATE orders SET  order_status= ? WHERE order_id = ?");
    $stmt->bind_param('si', $order_status, $order_id);
    if($stmt->execute()){
        header('location: index.php?order_updated=Order has been updated successfully');
    }else{
        header('location: index.php.php?order_error=Error occured, please try again.');
    }

    
}else{
    header('index.php');
    exit;
}
?>


<div class="container-fluid">
      <div class="row" style="min-height: 1000px">
            <?php 
              include_once('side_menu.php');  
            ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
              <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">

                    </div>

                </div>
              </div>
    <h2 class="mt-5 py-5">Edit Orders</h2>
    <div class="table-responsive me-5">

        <div class="mx-auto container">
            <form id="edit-form" method="POST" action="edit_order.php">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <div class="form-group mt-3">
                    <?php foreach($orders as $order) {?>

                    <input type="hidden" name="order_id" value="<?php echo $order['order_id'];?>">

                    <label>Order ID</label>
                    <p class="my-4"><?php echo $order['order_id'];?></p>
                </div>

                <div class="form-group mt-3">
                    <label>Order Price</label>
                    <p class="my-4"><?php echo $order['order_cost'];?></p>
                </div>

                <div class="form-group mt-3">
                    <label>Order Status</label>
                    <select class="form-select" required name="order_status">
                        
                        <option value="not paid" <?php if($order['order_status'] == 'not paid'){ echo "selected";}?>>Not Paid</option>
                        <option value="paid" <?php if($order['order_status'] == 'paid'){ echo "selected";}?>>Paid</option>
                        <option value="shipped" <?php if($order['order_status'] == 'shipped'){ echo "selected";}?>>Shipped</option>
                        <option value="delivered" <?php if($order['order_status'] == 'delivered'){ echo "selected";}?>>Delivered</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Order Date</label>
                    <p class="my-4"><?php echo $order['order_date'];?></p>
                </div>

                <div class="form-group mt-3">
                    <input type="submit" class="btn btn-primary" name="edit_btn" value="Edit">

                </div>

                <?php } ?>
            </form>
        </div>
    </div>
            </main>
      </div>
</div>
    
<?php include_once('../admin_layouts/admin_footer.php'); ?>