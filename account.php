<?php
  $page_title = 'Account';
  require_once('includes/load.php');
  //if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
  
?>

<?php include_once('layouts/header.php'); ?>
<?php ?>
<?php 

if(!isset($_SESSION['logged_in'])){
    header('location: login.php');
    exit;
}

include_once('sessionz.php');
//
// $timeout_duration = 10; // seconds


// if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    
//     session_unset();
//     session_destroy();
//     header('Location: login.php?error=Session expired. Please log in again.');
//     exit;
// }


// $_SESSION['last_activity'] = time();  

//
if(isset($_GET['logout'])){
    if(isset($_SESSION['logged_in'])){
        // unset($_SESSION['logged_in']);
        // unset($_SESSION['user_email']);
        // unset($_SESSION['user_name']);
        session_start();  
        session_unset();  
        session_destroy(); 
        header('location: login.php');
        exit;
    }
}


//Change pass
if(isset($_POST['change_password'])){
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    
    if($password !== $confirmPassword){
        header('location: account.php?error=Passwords do not match');
        exit; 
    }

    
    elseif(strlen($password) < 8) {
        header('location: account.php?error=Passwords must be at least 8 characters');
        exit;
    }

    elseif(!preg_match('/[A-Za-z]/', $password) || 
           !preg_match('/[0-9]/', $password) || 
           !preg_match('/[\W_]/', $password)) 
        { 
            header('location: account.php?error=Password must contain at least 1 uppercase letter, 1 number, and 1 special character');
        exit;
        }

        else {
            
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
            
            $stmt = $db->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
            $stmt->bind_param("ss", $hashedPassword, $user_email);
    
            if ($stmt->execute()) {
                
                header('location: account.php?message=Password updated successfully');
            } else {
                
                header('location: account.php?error=Error updating password');
            }
        }

}


//get order
if(isset($_SESSION['logged_in'])){
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $db->execute($stmt);
    
    $orders = $db->fetch_result($stmt);
}
?>



<!-- Account --> 
<section class="my-5 py-5">
    <div class="row container mx-auto">
        <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
            <h3 class="font-weight-bold">Account info</h3>
            <p class="text-center" style="color: green;"><?php if(isset($_GET['register_success'])){ echo $_GET['register_success']; }?></p>
            <p class="text-center" style="color: green;"><?php if(isset($_GET['login_success'])){ echo $_GET['login_success']; }?></p>
            <p class="text-center" style="color: green;"><?php if(isset($_GET['order_status'])){ echo $_GET['order_status']; }?></p>
            <hr class="mx-auto">
            <div class="account-info">
                <p>Name: <span><?php if(isset($_SESSION['user_name'])) {echo $_SESSION['user_name']; }?></span></p>
                <p>Email: <span><?php if(isset($_SESSION['user_email'])) {echo $_SESSION['user_email']; } ?></span></p>
                <p><a href="#orders" id="orders-btn">Your orders</a></p>
                <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
            </div>
        </div>

        <div class="text-center col-lg-6 col-md-12 col-sm-12">
            <form id="account-form" method="POST" action="account.php">
                <p class="text-center" style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <p class="text-center" style="color: green;"><?php if(isset($_GET['message'])){ echo $_GET['message']; }?></p>
                <h3>Change password</h3>
                <hr class="mx-auto">
                <div class="form-group">
                    <label>Password</label><i class="fas fa-eye-slash" style="cursor: pointer;" id="eyeicon-password"></i>
                    <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required/>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label><i class="fas fa-eye-slash" style="cursor: pointer;" id="eyeicon-confirm-password"></i>
                    <input type="password" class="form-control" id="account-confirm-password" name="confirmPassword" placeholder="Confirm Password" required/>
                </div>

                <div class="form-group">
                    <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn"/>
                </div>

            </form>
        </div>
    </div>
</section>


<!-- Orders -->
<section id="orders" class="orders container my-5 py-3">
    <div class="container mt-2">
        <h2 class="font-weight-bold text-center">Your cart</h2>
        <hr class="mx-auto">
    </div>

    <table class="mt-5 pt-5">
        <tr style="background-color: #fb774b; width: 100%;">
            <th style="text-align: center;">Order id</th>
            <th>Order cost</th>
            <th>Order status</th>
            <th style="text-align: right; padding: 5px 50px;">Order date</th>
            <th style="text-align: right; padding: 5px 50px;">Order details</th>
        </tr>
        <?php while($row = $orders->fetch_assoc()) { ?>

                <tr style="border: 1px solid black;">
                    <td style="text-align: center; background-color: lightblue;">
                        <span><?php echo $row['order_id'];?></span>
                    </td>

                    <td>
                        <span><?php echo $row['order_cost'];?></span>
                    </td>

                    <td>
                        <span><?php echo $row['order_status'];?></span>
                    </td>

                    <td>
                        <span><?php echo $row['order_date'];?></span>
                    </td>

                    <td>
                        <form method="POST" action="order_details.php">
                            <input type="hidden" value="<?php echo $row['order_status'];?>" name="order_status">
                            <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id">
                            <input class="btn order-details-btn" name="order_details_btn" style="color: white; background-color: #fb774b;" type="submit" value="details">
                        </form>
                    </td>

                </tr>
        <?php } ?>
    </table>
</section>





<script>
    let eyeiconPassword = document.getElementById("eyeicon-password");
    let passwordField = document.getElementById("account-password");
    let eyeiconConfirmPassword = document.getElementById("eyeicon-confirm-password");
    let confirmPasswordField = document.getElementById("account-confirm-password");

    eyeiconPassword.onclick = function () {
        if (passwordField.type === "password") {
          
            passwordField.type = "text";
            eyeiconPassword.classList.add("fa-eye");
            eyeiconPassword.classList.remove("fa-eye-slash");
        } else {
         
            passwordField.type = "password";
            eyeiconPassword.classList.add("fa-eye-slash");
            eyeiconPassword.classList.remove("fa-eye");
        }
    };
    eyeiconConfirmPassword.onclick = function () {
        if (confirmPasswordField.type === "password") {
           
            confirmPasswordField.type = "text";
            eyeiconConfirmPassword.classList.add("fa-eye");
            eyeiconConfirmPassword.classList.remove("fa-eye-slash");
        } else {
          
            confirmPasswordField.type = "password";
            eyeiconConfirmPassword.classList.add("fa-eye-slash");
            eyeiconConfirmPassword.classList.remove("fa-eye");
        }
    };


    let uppercaseCheck = document.getElementById("uppercaseCheck");
    let lowercaseCheck = document.getElementById("lowercaseCheck");
    let numberCheck = document.getElementById("numberCheck");
    let minLengthCheck = document.getElementById("minLengthCheck");
    let specialCharCheck = document.getElementById("specialCharCheck");

    function validatePasswords() {
    let password = passwordField.value;
    let confirmPassword = confirmPasswordField.value;

    // Check for at least 8 characters
    if (password.length >= 8) {
        minLengthCheck.style.color = 'green';
    } else {
        minLengthCheck.style.color = '';
    }

    // Check for uppercase letter
    if (/[A-Z]/.test(password)) {
        uppercaseCheck.style.color = 'green';
    } else {
        uppercaseCheck.style.color = '';
    }

    // Check for lowercase letter
    if (/[a-z]/.test(password)) {
        lowercaseCheck.style.color = 'green';
    } else {
        lowercaseCheck.style.color = '';
    }

    // Check for number
    if (/\d/.test(password)) {
        numberCheck.style.color = 'green';
    } else {
        numberCheck.style.color = '';
    }

    // Check for special character
    if (/[\W_]/.test(password)) {
        specialCharCheck.style.color = 'green';
    } else {
        specialCharCheck.style.color = '';
    }

    // Check if passwords match
    if (password === confirmPassword) {
        confirmPasswordField.style.borderColor = 'green'; 
    } else {
        confirmPasswordField.style.borderColor = ''; 
    }
}


passwordField.addEventListener('input', validatePasswords);
confirmPasswordField.addEventListener('input', validatePasswords);
</script>




<?php include_once('layouts/footer.php'); ?>