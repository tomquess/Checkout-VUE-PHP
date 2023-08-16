<?php

use PHPUnit\Framework\TestCase;
require_once 'includes/ValidationException.inc.php';
require_once 'classes/validation.class.php';

class ValidationTest extends TestCase
{
    public function testValidateNameValid()
    {
        $this->expectNotToPerformAssertions();
        Validation::validateName('Adam');
    }

    public function testValidateNameEmpty()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Name is required.");
        Validation::validateName('');
    }

    public function testValidateNameInvalidCharacters()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Name should only contain letters, spaces, and hyphens.");
        Validation::validateName('Adam420');
    }

    public function testValidateNameTooShort()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Name should be between 2 and 50 characters long.");
        Validation::validateName('J');
    }

    public function testValidateNameTooLong()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Name should be between 2 and 50 characters long.");
        Validation::validateName('ThisIsAVeryLongNameThatExceedsTheCharacterLimitThisIsAVeryLongNameThatExceedsTheCharacterLimit');
    }

    public function testValidateAltAddressValid()
    {
        $this->expectNotToPerformAssertions();
        Validation::validateAltAddress(
            true,
            'AdressALT 25',
            '12-345',
            'Miasto ALT'
        );
    }

    public function testValidateAltAddressMissingAddress()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Alt Address is required.");
        Validation::validateAltAddress(
            true,
            '',
            '12-345',
            'Miasto ALT'
        );
    }


}