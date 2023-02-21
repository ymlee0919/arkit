<?php
namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;
/**
 * Class IntValidator
 */
class IntValidator extends FieldValidator {

    /**
     * @return $this
     */
    public function check() : self
    {
        if(!$this->validField)
            return $this;

        if(false === filter_var($this->value, FILTER_VALIDATE_INT))
            return $this->registerError('invalid_integer');

        $this->realValue = intval($this->value);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getValue() : ?int
    {
        return $this->realValue;
    }

    /**
     * @param integer $value
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function greaterThan(int $value, bool $equal = true) : self
    {
        if(!$this->validField)
            return $this;

        if(false === filter_var($value, FILTER_VALIDATE_INT))
            throw new \InvalidArgumentException('The value to compare for, must be an integer', 511);

        $value = intval($value);

        if($equal)
        {
            if($this->realValue < $value)
                return $this->registerError('integer_grader_and_equal', ['value' => $value]);
        }
        else
        {
            if($this->realValue <= $value)
                return $this->registerError('integer_grader', ['value' => $value]);
        }

        return $this;
    }

    /**
     * @param integer $value
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function lessThan(int $value, bool $equal = true) : self
    {
        if(!$this->validField)
            return $this;

        if(false === filter_var($value, FILTER_VALIDATE_INT))
            throw new \InvalidArgumentException('The value to compare for, must be an integer', 511);

        $value = intval($value);

        if($equal)
        {
            if($this->realValue > $value)
                $this->registerError('integer_less_and_equal', ['value' => $value]);
        }
        else
        {
            if($this->realValue >= $value)
                return $this->registerError('integer_less', ['value' => $value]);
        }

        return $this;
    }

    /**
     * @param integer $min
     * @param integer $max
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function between(int $min, int $max, bool $equal = true) : self
    {
        if(!$this->validField)
            return $this;

        if(false === filter_var($min, FILTER_VALIDATE_INT))
            throw new \InvalidArgumentException('The minimum value to compare for, must be an integer', 511);

        if(false === filter_var($max, FILTER_VALIDATE_INT))
            throw new \InvalidArgumentException('The maximum value to compare for, must be an integer', 511);

        $min = intval($min);
        $max = intval($max);

        if($min >= $max)
            throw new \InvalidArgumentException('Invalid range to compare for', 511);

        if($equal)
        {
            if($this->realValue < $min || $this->realValue > $max)
                return $this->registerError('integer_between_and_equal', ['min' => $min, 'max' => $max]);
        }
        else
        {
            if($this->realValue <= $min || $this->realValue >= $max)
                return $this->registerError('integer_between', ['min' => $min, 'max' => $max]);
        }

        return $this;
    }

    /**
     * @param integer $min
     * @param integer $max
     * @param bool $notEqual
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function notBetween(int $min, int $max, bool $notEqual = true) : self
    {
        if(!$this->validField)
            return $this;

        if(false === filter_var($min, FILTER_VALIDATE_INT))
            throw new \InvalidArgumentException('The minimum value to compare for, must be an integer', 511);

        if(false === filter_var($max, FILTER_VALIDATE_INT))
            throw new \InvalidArgumentException('The maximum value to compare for, must be an integer', 511);

        $min = intval($min);
        $max = intval($max);

        if($min >= $max)
            throw new \InvalidArgumentException('Invalid range to compare for', 511);

        if($notEqual)
        {
            if($this->realValue > $min && $this->realValue < $max)
                return $this->registerError('integer_not_between_and_equal', ['min' => $min, 'max' => $max]);
        }
        else
        {
            if($this->realValue >= $min && $this->realValue <= $max)
                return $this->registerError('integer_not_between', ['min' => $min, 'max' => $max]);
        }

        return $this;
    }

    /**
     * @param bool $zeroIncluded Indicate if the 0 value can be included
     * @return $this
     */
    public function isPositive(bool $zeroIncluded = false) : self
    {
        if(!$this->validField)
            return $this;

        if(!!$zeroIncluded)
        {
            if($this->realValue < 0)
                return $this->registerError('integer_positive');
        }
        else
        {
            if($this->realValue <= 0)
                return $this->registerError('integer_positive');
        }

        return $this;
    }

    /**
     * @param bool $zeroIncluded Indicate if the 0 value can be included
     * @return $this
     */
    public function isNegative(bool $zeroIncluded = false) : self
    {
        if(!$this->validField)
            return $this;

        if(!!$zeroIncluded)
        {
            if($this->realValue > 0)
                return $this->registerError('integer_negative');
        }
        else
        {
            if($this->realValue >= 0)
                return $this->registerError('integer_negative');
        }

        return $this;
    }
}