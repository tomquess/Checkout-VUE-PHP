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
            header("location: ../index.php?error-stmtfailed");
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
        $products,
        $partial_price,
        $full_price,
        $comments,
        $newsletter,
        $law,
        $delivery_code
    ) {
        // Name validation -------------------
        if (empty($name)) {
            throw new ValidationException("Name is required.");
        }

        // Ensure name contains only letters, spaces, and hyphens
        if (!preg_match('/^[A-Za-z\-\'ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]+$/u', $name)) {
            throw new ValidationException("Name should only contain letters, spaces, and hyphens.");
        }

        // Ensure name is between 2 and 50 characters long
        $nameLength = strlen($name);
        if ($nameLength < 2 || $nameLength > 50) {
            throw new ValidationException("Name should be between 2 and 50 characters long.");
        }
        // Surname validation ------------------
        if (empty($surname)) {
            throw new ValidationException("Surname is required.");
        }

        // Ensure Surname contains only letters, spaces, and hyphens
        if (!preg_match('/^[A-Za-z\-\'ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]+$/u', $surname)) {
            throw new ValidationException("Surname should only contain letters, spaces, and hyphens.");
        }

        // Ensure surname is between 2 and 50 characters long
        $surnameLength = strlen($surname);
        if ($surnameLength < 2 || $surnameLength > 50) {
            throw new ValidationException("Surname should be between 2 and 50 characters long.");
        }
        // Nation validation ------------------
        if (empty($nation)) {
            throw new ValidationException("Nation is required.");
        }

        // Ensure Nation is one from the listed options in front end
        if ($nation != "poland" && $nation != "other") {
            throw new ValidationException("Nation not permitted");
        }
        // Address validation ------------------
        if (empty($address)) {
            throw new ValidationException("Address is required.");
        }
        // Ensure Address is good (i only taken into consideration Polish format)
        if (!preg_match('/^[\p{L}\d\s\-\.,]+ [\p{L}\d\s\-\.,\/]+$/u', $address) && $nation = 'poland') {
            throw new ValidationException("Address is invalid.");
        }
        // Ensure Address is between 2 and 50 characters long
        $addressLength = strlen($address);
        if ($addressLength < 2 || $addressLength > 50) {
            throw new ValidationException("Address should be between 2 and 50 characters long.");
        }
        // Postcode validation ------------------
        if (empty($postcode)) {
            throw new ValidationException("Postcode is required.");
        }
        // Ensure postcode is good (i only taken into consideration Polish format for testing)
        if (!preg_match('/^\d{2}-\d{3}$/', $postcode)) {
            throw new ValidationException("Postcode is invalid.");
        }
        // Ensure Address is between 2 and 50 characters long
        $postcodeLength = strlen($postcode);
        if ($postcodeLength < 2 || $postcodeLength > 12) {
            throw new ValidationException("Postcode should be between 2 and 12 characters long.");
        }
        // City validation ------------------
        if (empty($city)) {
            throw new ValidationException("City is required.");
        }
        // Ensure city is good 
        if (!preg_match('/^[\p{L}\s\-]+$/u', $city)) {
            throw new ValidationException("City is invalid.");
        }
        // Ensure city is between 2 and 255 characters long
        $cityLength = strlen($city);
        if ($cityLength < 2 || $cityLength > 255) {
            throw new ValidationException("City should be between 2 and 255 characters long.");
        }
        // Phonenumber validation ------------------
        if (empty($phonenumber)) {
            throw new ValidationException("Phonenumber is required.");
        }
        // Ensure Phonenumber is good (with optional country code)
        if (!preg_match('/^\+?\d{0,4}\d{9}$/', $phonenumber)) {
            throw new ValidationException("Phonenumber is invalid.");
        }
        // Ensure Phonenumber is between 2 and 15 characters long
        $phonenumberLength = strlen($phonenumber);
        if ($phonenumberLength < 9 || $phonenumberLength > 15) {
            throw new ValidationException("Phonenumber should be between 9 and 15 characters long.");
        }

        // Alt address validation ---------------
        if ($alt_delivery == true) {
            // Alt-Address validation ------------------
            if (empty($alt_address)) {
                throw new ValidationException("Alt Address is required.");
            }
            // Ensure Address is good (i only taken into consideration Polish format)
            if (!preg_match('/^[\p{L}\d\s\-\.,]+ [\p{L}\d\s\-\.,\/]+$/u', $alt_address) && $nation = 'poland') {
                throw new ValidationException("Alt Address is invalid.");
            }
            // Ensure Address is between 2 and 50 characters long
            $alt_addressLength = strlen($alt_address);
            if ($alt_addressLength < 2 || $alt_addressLength > 50) {
                throw new ValidationException("Alt Address should be between 2 and 50 characters long.");
            }

            // Postcode validation ------------------
            if (empty($alt_postcode)) {
                throw new ValidationException("Alt Postcode is required.");
            }
            // Ensure postcode is good (i only taken into consideration Polish format for testing)
            if (!preg_match('/^\d{2}-\d{3}$/', $alt_postcode)) {
                throw new ValidationException("Alt Postcode is invalid.");
            }
            // Ensure Address is between 2 and 50 characters long
            $alt_postcodeLength = strlen($alt_postcode);
            if ($alt_postcodeLength < 2 || $alt_postcodeLength > 12) {
                throw new ValidationException("Alt Postcode should be between 2 and 12 characters long.");
            }
            // City validation ------------------
            if (empty($alt_city)) {
                throw new ValidationException("Alt City is required.");
            }
            // Ensure city is good 
            if (!preg_match('/^[\p{L}\s\-]+$/u', $alt_city)) {
                throw new ValidationException("Alt City is invalid.");
            }
            // Ensure city is between 2 and 255 characters long
            $alt_cityLength = strlen($alt_city);
            if ($alt_cityLength < 2 || $alt_cityLength > 255) {
                throw new ValidationException("Alt City should be between 2 and 255 characters long.");
            }
        }
        // Delivery type validation ------------------
        if (empty($delivery_type)) {
            throw new ValidationException("Delivery type is required.");
        }
        // Ensure delivery type is one of the present options
        if ($delivery_type != 'dpd' && $delivery_type != 'paczkomat' && $delivery_type != 'dpdpobranie') {
            throw new ValidationException("Delivery type is invalid.");
        }
        // Payment type validation ------------------
        if (empty($payment_type)) {
            throw new ValidationException("Payment type is required.");
        }
        // Ensure payment type is one of the present options
        if ($payment_type != 'payu' && $payment_type != 'upon-receipt' && $payment_type != 'bank-transfer') {
            throw new ValidationException("Payment type is invalid.");
        }
        // Ensure payment type is compatible for delivery option
        if ($delivery_type == 'dpdpobranie' && $payment_type != 'upon-receipt') {
            throw new ValidationException("Payment type must be upon-receipt when using dpd pobranie delivery type.");
        }

        /*

        Where i would put products, price, and full price after discount validation, but i dont think implementing it is a good idea since there is only mock products list in the front end

        expand this section if products db table is made

        */

        // Ensure newsletter is checked or unchecked
        if ($newsletter != true && $newsletter != false) {
            throw new ValidationException("Newsletter is invalid.");
        }
        // Ensure that law is checked
        if ($law != true) {
            throw new ValidationException("Law option must be checked for form to proceed.");
        }

    }
}
