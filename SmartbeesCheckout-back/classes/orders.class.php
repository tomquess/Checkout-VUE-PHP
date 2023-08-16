<?php

class Orders extends Dbh
{
    protected function setOrder(
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
    ) {
        $sql = "INSERT INTO orders (
            name, 
            surname, 
            nation,
            address,
            postcode,
            city,
            phonenumber, 
            alt_delivery, 
            alt_address, 
            alt_postcode, 
            alt_city, 
            delivery_type, 
            payment_type, 
            products, 
            partial_price, 
            full_price, 
            comments, 
            newsletter, 
            law, 
            delivery_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute([
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
        ])) {
            $stmt = null;
            http_response_code(400);
            echo json_encode(['error' => 'Sql error']);
            exit();
        }

        $stmt = null;
    }

    protected function validation(
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
        $newsletter,
        $law
    ) {
        Validation::validateName($name);
        Validation::validateSurname($surname);
        Validation::validateNation($nation);
        Validation::validateAddress($address);
        Validation::validatePostcode($postcode);
        Validation::validateCity($city);
        Validation::validatePhonenumber($phonenumber);
        Validation::validateAltAddress($alt_delivery, $alt_address, $alt_postcode, $alt_city);
        Validation::validateDeliverytype($delivery_type);
        Validation::validatePaymenttype($payment_type, $delivery_type);
        Validation::validateChecks($newsletter, $law);
    }
}
