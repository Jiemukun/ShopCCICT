<?php
//
$timeout_duration = 600; // seconds


if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    
    session_unset();
    session_destroy();
    header('Location: login.php?error=Session expired. Please log in again.');
    exit;
}


$_SESSION['last_activity'] = time();  

//

?>