<?php

namespace Arkit\Core\Filter\Input\Validator;

/**
 * Credit / Debit card types
 */
enum Card : string
{
    /**
     * VisaElectro Card
     */
    case VISAELECTRON = 'visaelectron';

    /**
     * Maestro Card
     */
	case MAESTRO = 'maestro';

    /**
     * Forbrugsforeningen Card
     */
	case FORBRUGSFORENINGEN = 'forbrugsforeningen';

    /**
     * Dankort Card
     */
	case DANKORT = 'dankort';

    /**
     * Visa Card
     */
	case VISA = 'visa';

    /**
     * MasterCard Card
     */
	case MASTERCARD = 'mastercard';

    /**
     * American Express Card
     */
	case AMEX = 'amex';

    /**
     * DinersClub Card
     */
	case DINERSCLUB = 'dinersclub';

    /**
     * Discover Card
     */
	case DISCOVER = 'discover';

    /**
     * UnionPay Card
     */
	case UNIONPAY = 'unionpay';

    /**
     * JBC Card
     */
	case JCB = 'jcb';
}