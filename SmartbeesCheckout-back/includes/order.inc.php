<?php
header("Access-Control-Allow-Origin: *");   // Can insert front-end domain; Used * for testing purposes
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recaptcha validation before any other code, to terminate on error before wasting resources
    $recaptchaSecretKey = '6LcqOaknAAAAAOUlTnBOSnYnxhkGOQoWKIHJBWvk';
    $recaptchaToken = $_POST['reCaptcha']; // Name of the field containing the token in the POST request


    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaToken
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $resultRecaptcha = file_get_contents($url, false, $context);

    if ($resultRecaptcha !== false) {
        $resultRecaptcha = json_decode($resultRecaptcha, true);
        if (!$resultRecaptcha['success']) {
            // reCAPTCHA verification failed
            // Handle the error
            http_response_code(400);
            echo json_encode(['error' => 'Captcha failed: verification not passed']);
            exit();
        }
    } else {
        // Error while verifying reCAPTCHA
        // Handle the error
        http_response_code(400);
        echo json_encode(['error' => 'Captcha failed: error while verifying']);
        exit();
    }


    //Grab the data from form
    $name = isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : null;
    $surname = isset($_POST["surname"]) ? htmlspecialchars($_POST["surname"]) : null;
    $nation = isset($_POST["nation"]) ? htmlspecialchars($_POST["nation"]) : null;
    $address = isset($_POST["address"]) ? htmlspecialchars($_POST["address"]) : null;
    $postcode = isset($_POST["postcode"]) ? htmlspecialchars($_POST["postcode"]) : null;
    $city = isset($_POST["city"]) ? htmlspecialchars($_POST["city"]) : null;
    $phonenumber = isset($_POST["phonenumber"]) ? htmlspecialchars($_POST["phonenumber"]) : null;
    $alt_delivery = isset($_POST["other_address_delivery"]) ? (bool)$_POST["other_address_delivery"] : false;
    $alt_address = isset($_POST["address-alt"]) ? htmlspecialchars($_POST["address-alt"]) : null;
    $alt_postcode = isset($_POST["postcode-alt"]) ? htmlspecialchars($_POST["postcode-alt"]) : null;
    $alt_city = isset($_POST["city-alt"]) ? htmlspecialchars($_POST["city-alt"]) : null;
    $delivery_type = isset($_POST["deliverytype"]) ? htmlspecialchars($_POST["deliverytype"]) : null;
    $payment_type = isset($_POST["paymenttype"]) ? htmlspecialchars($_POST["paymenttype"]) : null;
    $products = isset($_POST["products"]) ? $_POST["products"] : null;
    $partial_price = isset($_POST["partial_price"]) ? htmlspecialchars($_POST["partial_price"]) : null;
    $full_price = isset($_POST["full_price"]) ? htmlspecialchars($_POST["full_price"]) : null;
    $comments = isset($_POST["comment"]) ? htmlspecialchars($_POST["comment"]) : null;
    $newsletter = isset($_POST["newsletter-signup-checkbox"]) ? (bool)$_POST["newsletter-signup-checkbox"] : false;
    $law = isset($_POST["law-checkbox"]) ? (bool)$_POST["law-checkbox"] : false;

    $timestamp = time();
    $delivery_code = strtoupper(substr($name, 0, 2) . substr($surname, 0, 2) . $timestamp);


    // Check products validity
    if ($products) {
        $decodedProducts = json_decode($products);
        if ($decodedProducts === null && json_last_error() !== JSON_ERROR_NONE) {
            // Handle invalid JSON data
            throw new ValidationException("Products json error.");
            exit();
        }
    }

    //Instantiate classes for Orders class purposes, and validation,
    include "../classes/dbh.class.php";
    include "../classes/orders.class.php";
    include "../classes/orderscontr.class.php";
    include "ValidationException.inc.php";
    try {
        $order = new OrdersContr(
            $name,
            $surname,
            $nation,
            $address,
            $postcode,
            $city,
            $phonenumber,
            $alt_delivery,
            $alt_address,
            $alt_postcode,
            $alt_city,
            $delivery_type,
            $payment_type,
            $products,
            $partial_price,
            $full_price,
            $comments,
            $newsletter,
            $law,
            $delivery_code
        );
        $order->validateOrder();  // Validate most of the data provided
        $order->createOrder();  // Save to data base if no exceptions thrown by validator
        $response = [
            'message' => 'Zamówienie złożone. Twój numer zamówienia to:',
            'deliverycode' => $delivery_code
        ];  // Set response msg
    } catch (ValidationException $e)  // Validation related exceptions handling
    {
        http_response_code(400);
        $response = $e->errorMessage();
    } catch (PDOException $e)   // PDO related exceptions handling
    {
        $response = ['error' => 'Nie można złożyć zamówienia: ' . $e->getMessage()];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {  // This happens if connection method is not set to POST
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
}
