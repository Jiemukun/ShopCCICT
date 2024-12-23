<?php
$page_title = 'Payment';
require_once('includes/load.php');
include_once('layouts/header.php');


if (isset($_POST['order_total_price']) && isset($_POST['order_status']) && isset($_POST['order_id'])) {
    $order_total_price = $_POST['order_total_price'];
    $order_status = $_POST['order_status'];
    $order_id = $_POST['order_id'];
} else {
    
    echo "Invalid request.";
    exit;
}
// if ($order_total_price > 0 && $order_status == 'not paid')
if ($order_total_price > 0 && $order_status == 'not paid') {
    
    $data = [
        'data' => [
            'attributes' => [
                'amount' => $order_total_price * 100,  
                'description' => 'Order #' . $order_id,
                'remarks' => 'Order payment'
            ]
        ]
    ];

    
    $apiKey = 'sk_test_bW3x9HNHdaxMX1LS88md3uMG'; 
    $encodedKey = base64_encode($apiKey);

    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/links",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic " . $encodedKey, 
            "Content-Type: application/json"
        ],
    ]);

    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        
        $response_data = json_decode($response, true);

        
        echo "<pre>";
        var_dump($response_data);
        echo "</pre>";

        
        if (isset($response_data['data']['attributes']['checkout_url'])) {
            $payment_url = $response_data['data']['attributes']['checkout_url'];

            
            header("Location: " . $payment_url);
            exit();  
        } else {
            echo "Error: Unable to generate payment link. Response: " . json_encode($response_data);
        }
    }
} else {
    echo "Invalid order details or the order is already paid.";
}

include_once('layouts/footer.php');
?>
