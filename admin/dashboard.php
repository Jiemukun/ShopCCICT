<?php
  $page_title = 'Admin Dashboard';
  require_once('../includes/load.php');  
  include_once('../admin_layouts/admin_header.php');  
  //
  include_once('session.php');
  //
  if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
  }

  // total sales for 'Not Paid' orders
  $stmt = $db->prepare("SELECT SUM(order_cost) AS total_sales FROM orders WHERE order_status = 'not paid'");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_not_paid = $data['total_sales'] ? $data['total_sales'] : 0;

  // total sales for 'Paid' orders
  $stmt = $db->prepare("SELECT SUM(order_cost) AS total_sales FROM orders WHERE order_status = 'paid'");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_paid = $data['total_sales'] ? $data['total_sales'] : 0;

  // total sales for 'Shipped' orders
  $stmt = $db->prepare("SELECT SUM(order_cost) AS total_sales FROM orders WHERE order_status = 'shipped'");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_shipped = $data['total_sales'] ? $data['total_sales'] : 0;

  // total sales for 'Delivered' orders
  $stmt = $db->prepare("SELECT SUM(order_cost) AS total_sales FROM orders WHERE order_status = 'delivered'");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_delivered = $data['total_sales'] ? $data['total_sales'] : 0;

  // total sales across all order statuses
  $stmt = $db->prepare("SELECT SUM(order_cost) AS total_sales FROM orders WHERE order_status IN ('not paid', 'paid', 'shipped', 'delivered')");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales = $data['total_sales'] ? $data['total_sales'] : 0;
?>

<div class="container-fluid">
  <div class="row">
    <?php include_once('side_menu.php'); ?>  
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">Dashboard</h1>
      </div>

      <h2 class="mt-5 py-5">Sales Overview</h2>

      
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Total Sales (All Time)</h5>
            </div>
            <div class="card-body">
              <p><strong>₱<?php echo number_format($total_sales, 2); ?></strong></p>
            </div>
          </div>
        </div>

        <!--  'Not Paid' orders -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Sales - Not Paid</h5>
            </div>
            <div class="card-body">
              <p><strong>₱<?php echo number_format($total_sales_not_paid, 2); ?></strong></p>
            </div>
          </div>
        </div>

        <!--  for 'Paid' orders -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Sales - Paid</h5>
            </div>
            <div class="card-body">
              <p><strong>₱<?php echo number_format($total_sales_paid, 2); ?></strong></p>
            </div>
          </div>
        </div>
      </div>

      <!-- for 'Shipped' orders -->
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Sales - Shipped</h5>
            </div>
            <div class="card-body">
              <p><strong>₱<?php echo number_format($total_sales_shipped, 2); ?></strong></p>
            </div>
          </div>
        </div>

        <!--for 'Delivered' orders -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Sales - Delivered</h5>
            </div>
            <div class="card-body">
              <p><strong>₱<?php echo number_format($total_sales_delivered, 2); ?></strong></p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>


<?php include_once('../admin_layouts/admin_footer.php'); ?>