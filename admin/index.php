<?php
  $page_title = 'Admin';
require_once('../includes/load.php');  
?>
<?php include_once('../admin_layouts/admin_header.php'); 
//
include_once('session.php');
//
?>

<?php 
if (!isset($_SESSION['admin_logged_in'])){
  header('location: login.php');
  exit;
}
?>



<?php
//get order

      if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
        $page_no = $_GET['page_no'];
      }else{
        $page_no = 1;
      }

      $stmt1 = $db->prepare("SELECT COUNT(*) As total_records FROM orders");
      $stmt1->execute();
      $stmt1->bind_result($total_records);
      $stmt1->store_result();
      $stmt1->fetch();

      //prod per page
      $total_records_per_page = 10;
      $offset = ($page_no-1) * $total_records_per_page;
      $previous_page = $page_no - 1;
      $next_page = $page_no + 1;

      $adjacents = "2";

      $total_no_of_pages = ceil($total_records/$total_records_per_page);

      //get all prod
      $stmt2 = $db->prepare("SELECT * FROM orders LIMIT $offset, $total_records_per_page");
      $stmt2->execute();
      $orders = $stmt2->get_result();


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

            

<h2 class="mt-5 py-5">Orders</h2>
<?php if(isset($_GET['order_updated'])) {?>
  <p class="text-center" style="color: green;"><?php echo $_GET['order_updated'];?></p>
<?php } ?>

<?php if(isset($_GET['order_error'])) {?>
  <p class="text-center" style="color: red;"><?php echo $_GET['order_error'];?></p>
<?php } ?>

<?php if(isset($_GET['deleted_successfully'])) {?>
  <p class="text-center" style="color: red;"><?php echo $_GET['deleted_successfully'];?></p>
<?php } ?>

<?php if(isset($_GET['deleted_error'])) {?>
  <p class="text-center" style="color: red;"><?php echo $_GET['deleted_error'];?></p>
<?php } ?>
<div class="table-responsive me-5">
  <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th scope="col">Order ID</th>
          <th scope="col">Order Status</th>
          <th scope="col">User ID</th>
          <th scope="col">Order Date</th>
          <th scope="col">User Phone</th>
          <th scope="col">User Address</th>
          <th scope="col">Edit</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($orders as $order) {?>
        <tr>
          <td><?php echo $order['order_id']; ?></td>
          <!-- <td><?php echo $order['order_status']; ?></td> -->
          <td style="color: 
    <?php 
        if ($order['order_status'] === 'paid') {
            echo 'green'; 
        } elseif ($order['order_status'] === 'not paid') {
            echo 'red'; 
        } elseif ($order['order_status'] === 'shipped') {
            echo 'orange';
        } elseif ($order['order_status'] === 'delivered') {
            echo 'purple';
        } else {
            echo 'black'; // Default color if no status matches
        }
    ?>; font-weight: bold;">
    <?php echo $order['order_status']; ?>
</td>




          <td><?php echo $order['user_id']; ?></td>
          <td><?php echo $order['order_date']; ?></td>
          <td><?php echo $order['user_phone']; ?></td>
          <td><?php echo $order['user_address']; ?></td>
          <td><a class="btn btn-primary" href="edit_order.php?order_id=<?php echo $order['order_id'];?>">Edit</a></td>
          <!-- <td><a class="btn btn-danger" href="delete_order.php?order_id=<?php echo $order['order_id'];?>">Delete</a></td> -->
          <td><a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $order['order_id']; ?>)">Delete</a></td>
        </tr>
        <?php } ?>
      </tbody>
  </table>

  <!--Page OR Pagination-->
  <nav aria-label="Page navigation example">
                <ul class="pagination mt-5">
                    <li class="page-item <?php if($page_no<=1){echo 'disabled';}?>">
                        <a class="page-link" href="<?php if($page_no <= 1) { echo '#'; }else{ echo "?page_no=".($page_no-1);}?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                    <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>

                    <?php if($page_no >=3) {?>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="<?php echo "?page_no=" . $page_no;?>"><?php echo ($page_no);?></a></li>
                    <?php } ?>


                    <li class="page-item <?php if($page_no >= $total_no_of_pages){ echo 'disabled';}?>">
                        <a class="page-link" href="<?php if($page_no >= $total_no_of_pages) {echo '#';} else{ echo "?page_no=" . ($page_no+1);}?>">Next</a></li>

                </ul>
            </nav>
          </div>
        </main>
      </div>
</div>

<script type="text/javascript">
    function confirmDelete(orderId) {
        
        var confirmation = confirm("Are you sure you want to delete this order?");
        
        
        if (confirmation) {
            window.location.href = "delete_order.php?order_id=" + orderId;
        }
    }
</script>


<?php include_once('../admin_layouts/admin_footer.php'); ?>