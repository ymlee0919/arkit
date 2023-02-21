<?php

namespace ArkitTest\InputValidation;

use PHPUnit\Framework\TestCase;

class CreditCardValidationTest extends ValidationTest
{
    public function basicCardInfoProvider() : \ArkitTest\Provider\CreditCardProvider
    {
        return new \ArkitTest\Provider\CreditCardProvider(\ArkitTest\Provider\CreditCardProvider::BASIC_INFORMATION);
    }

    public function cvcCardInfoProvider() : \ArkitTest\Provider\CreditCardProvider
    {
        return new \ArkitTest\Provider\CreditCardProvider(\ArkitTest\Provider\CreditCardProvider::CVC_INFORMATION);
    }

    public function expiryCardInfoProvider() : \ArkitTest\Provider\CreditCardProvider
    {
        return new \ArkitTest\Provider\CreditCardProvider(\ArkitTest\Provider\CreditCardProvider::EXPIRY_INFORMATION);
    }

    /**
     * @dataProvider basicCardInfoProvider
     * @covers \Arkit\Core\Filter\Input\Validator\CreditCardValidator
     */
    public function testValidCreditCard(string $type, string $number)
    {
        $valid = $this->validator->validate('credit_card', $number)->isCreditCard()->is($type)->isValid();
        $this->assertTrue($valid, "The card number {$number} is not a valid {$type} card");
    }

    /**
     * @dataProvider cvcCardInfoProvider
     * @covers \Arkit\Core\Filter\Input\Validator\CreditCardValidator
     */
    public function testCVC(string $type, string $number, int $cvc)
    {
        $valid = $this->validator->validate('credit_card', $number)->isCreditCard()->is($type)->isValidCvc($cvc)->isValid();
        $this->assertTrue($valid, "The card {$number} ({$type}) have not a valid CVC code: {$cvc}");
    }

    /**
     * @dataProvider expiryCardInfoProvider
     * @covers \Arkit\Core\Filter\Input\Validator\CreditCardValidator
     */
    public function testExpirationDate(string $type, string $number, int $expMonth, int $expYear)
    {
        $valid = $this->validator->validate('credit_card', $number)->isCreditCard()->is($type)->isValidExpDate($expYear, $expMonth)->isValid();
        $this->assertTrue($valid, "The card {$number} ({$type}) have expired: {$expMonth}/{$expYear}");
    }

}