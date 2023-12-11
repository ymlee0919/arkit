<?php

namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;

define('CARD_VISAELECTRON', 'visaelectron');
define('CARD_MAESTRO', 'maestro');
define('CARD_FORBRUGSFORENINGEN', 'forbrugsforeningen');
define('CARD_DANKORT', 'dankort');
define('CARD_VISA', 'visa');
define('CARD_MASTERCARD', 'mastercard');
define('CARD_AMEX', 'amex');
define('CARD_DINERSCLUB', 'dinersclub');
define('CARD_DISCOVER', 'discover');
define('CARD_UNIONPAY', 'unionpay');
define('CARD_JCB', 'jcb');

class CreditCardValidator extends FieldValidator{

    /**
     * @var string|null
     */
    private ?string $type = null;

    /**
     * @var array|array[]
     */
    private static array $cards = array(
        // Debit cards must come first, since they have more specific patterns than their credit-card equivalents.
        'visaelectron' => array(
            'type' => 'visaelectron',
            'pattern' => '/^4(026|17500|405|508|844|91[37])/',
            'length' => array(16),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
        'maestro' => array(
            'type' => 'maestro',
            'pattern' => '/^(5(018|0[23]|[68])|6(39|7))/',
            'length' => array(12, 13, 14, 15, 16, 17, 18, 19),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
        'forbrugsforeningen' => array(
            'type' => 'forbrugsforeningen',
            'pattern' => '/^600/',
            'length' => array(16),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
        'dankort' => array(
            'type' => 'dankort',
            'pattern' => '/^5019/',
            'length' => array(16),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
        // Credit cards
        'visa' => array(
            'type' => 'visa',
            'pattern' => '/^4/',
            'length' => array(13, 16),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
        'mastercard' => array(
            'type' => 'mastercard',
            'pattern' => '/^(5[0-5]|2[2-7])/',
            'length' => array(16),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
        'americanexpress' => array(
            'type' => 'amex',
            'pattern' => '/^3[47]/',
            'format' => '/(\d{1,4})(\d{1,6})?(\d{1,5})?/',
            'length' => array(15),
            'cvcLength' => array(3, 4),
            'luhn' => true,
        ),
        'amex' => array(
            'type' => 'amex',
            'pattern' => '/^3[47]/',
            'format' => '/(\d{1,4})(\d{1,6})?(\d{1,5})?/',
            'length' => array(15),
            'cvcLength' => array(3, 4),
            'luhn' => true,
        ),
        'dinersclub' => array(
            'type' => 'dinersclub',
            'pattern' => '/^3[0689]/',
            'length' => array(14),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
        'discover' => array(
            'type' => 'discover',
            'pattern' => '/^6([045]|22)/',
            'length' => array(16),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
        'unionpay' => array(
            'type' => 'unionpay',
            'pattern' => '/^(62|88)/',
            'length' => array(16, 17, 18, 19),
            'cvcLength' => array(3),
            'luhn' => false,
        ),
        'jcb' => array(
            'type' => 'jcb',
            'pattern' => '/^35/',
            'length' => array(16),
            'cvcLength' => array(3),
            'luhn' => true,
        ),
    );

    /**
     * @return $this
     */
    public function check() : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;
        
        // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
        $number = preg_replace('/\D/', '', $this->value);
        // Set the string length and parity
        $number_length = strlen($number);
        $parity = $number_length % 2;
        // Loop through each digit and do the maths
        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $number[$i];
            // Multiply alternate digits by two
            if ($i % 2 == $parity) {
                $digit *= 2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) { $digit -= 9; }
            }
            // Total up the digits
            $total+=$digit;
        }

        // If the total mod 10 equals 0, the number is valid
        $this->validField = ($total % 10 == 0);

        if($this->validField)
            $this->realValue = $this->value;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function is(string $type) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        $cardType = strtolower($type);
        $cardType = str_replace(' ', '', $cardType);

        if(!isset(CreditCardValidator::$cards[$cardType]))
            throw new \InvalidArgumentException('Credit Card type not supported', 701);

        $number = preg_replace('/\D/', '', $this->value);

        // Check the length and pattern
        $length = strlen($number);
        if(!preg_match(CreditCardValidator::$cards[$cardType]['pattern'], $number) || !in_array($length, self::$cards[$cardType]['length']))
            $this->validField = $this->form->registerError('invalid_credit_card', ['type' => $type]);
        else
            $this->type = $cardType;

        return $this;
    }

    /**
     * @param string $cvc
     * @return $this
     */
    public function isValidCvc(string $cvc) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        if(!ctype_digit($cvc))
        {
            $this->validField = $this->form->registerError('invalid_credit_card_cvc');
            return $this;
        }

        if(!isset(CreditCardValidator::$cards[$this->type]))
            throw new \InvalidArgumentException('Credit Card type not supported', 701);

        foreach (self::$cards[$this->type]['cvcLength'] as $length) {
            if (strlen($cvc) == $length) {
                return $this;
            }
        }

        $this->validField = $this->form->registerError('invalid_credit_card_cvc');
        return $this;
    }

    /**
     * @param int $year
     * @param int $month
     * @return $this
     */
    public function isValidExpDate(int $year, int $month) : self
	{
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;
		
		$month = str_pad($month, 2, '0', STR_PAD_LEFT);

        if (! preg_match('/^20\d\d$/', $year)) {
            $this->validField = $this->form->registerError('invalid_credit_card_year_exp');
            return $this;
        }

        if (! preg_match('/^(0[1-9]|1[0-2])$/', $month)) {
            $this->validField = $this->form->registerError('invalid_credit_card_month_exp');
            return $this;
        }

        // past date
        if ($year < date('Y') || $year == date('Y') && $month < date('m')) {
            $this->validField = $this->form->registerError('credit_card_pass_exp');
            return $this;
        }

        return $this;
	}

    /**
     * @return mixed
     */
    public function getValue() : mixed
    {
        return $this->realValue;
    }
}