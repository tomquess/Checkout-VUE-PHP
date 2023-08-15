<?php

class DiscountcodesContr extends Discountcodes
{
    private $code;
    
    public function __construct(
        $code
    ) {
        $this->code = $code;
    }

    public function fetchDiscountcodeByCode()
    {
        return $this->getDiscountcodeByCode($this->code);
    }
}
