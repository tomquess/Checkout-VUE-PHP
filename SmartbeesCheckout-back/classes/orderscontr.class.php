<?php

class OrdersContr extends Orders
{
    private $name;
    private $surname;
    private $nation;
    private $address;
    private $postcode;
    private $city;
    private $phonenumber;
    private $alt_delivery;
    private $alt_address;
    private $alt_postcode;
    private $alt_city;
    private $delivery_type;
    private $payment_type;
    private $products;
    private $partial_price;
    private $full_price;
    private $comments;
    private $newsletter;
    private $law;
    private $delivery_code;

    public function __construct(
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
        $this->name = $name;
        $this->surname = $surname;
        $this->nation = $nation;
        $this->address = $address;
        $this->postcode = $postcode;
        $this->city = $city;
        $this->phonenumber = $phonenumber;
        $this->alt_delivery = $alt_delivery;
        $this->alt_address = $alt_address;
        $this->alt_postcode = $alt_postcode;
        $this->alt_city = $alt_city;
        $this->delivery_type = $delivery_type;
        $this->payment_type = $payment_type;
        $this->products = $products;
        $this->partial_price = $partial_price;
        $this->full_price = $full_price;
        $this->comments = $comments;
        $this->newsletter = $newsletter;
        $this->law = $law;
        $this->delivery_code = $delivery_code;
    }



    public function createOrder()
    {
        $this->setOrder(
            $this->name,
            $this->surname,
            $this->nation,
            $this->address,
            $this->postcode,
            $this->city,
            $this->phonenumber,
            $this->alt_delivery,
            $this->alt_address,
            $this->alt_postcode,
            $this->alt_city,
            $this->delivery_type,
            $this->payment_type,
            $this->products,
            $this->partial_price,
            $this->full_price,
            $this->comments,
            $this->newsletter,
            $this->law,
            $this->delivery_code
        );
    }

    public function validateOrder()
    {
        $this->validation(
            $this->name,
            $this->surname,
            $this->nation,
            $this->address,
            $this->postcode,
            $this->city,
            $this->phonenumber,
            $this->alt_delivery,
            $this->alt_address,
            $this->alt_postcode,
            $this->alt_city,
            $this->delivery_type,
            $this->payment_type,
            $this->newsletter,
            $this->law
        );
    }
}
