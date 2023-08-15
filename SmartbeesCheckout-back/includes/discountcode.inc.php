<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Grab the data
    $jsonData = file_get_contents('php://input'); // Read the raw JSON data from the request body
    $data = json_decode($jsonData, true); // Convert JSON to associative array
    if (isset($data["code"])) {
        $code = $data["code"];
        //Instantiate Discountcodes Controller class
        include "../classes/dbh.class.php";
        include "../classes/discountcodes.class.php";
        include "../classes/discountcodescontr.class.php";

        try {
            $discountcode = new DiscountcodesContr($code);   
            $response = $discountcode->fetchDiscountcodeByCode();
            
        } catch (PDOException $e) {
            $response = ['error' => 'Error: ' . $e->getMessage()];
        }
        // Echo out the data
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid input data']);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // Respond to preflight requests with a 200 OK status
    http_response_code(200);
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
}
