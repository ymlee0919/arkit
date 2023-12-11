<?php
namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;

/**
 * Class NumericValidator
 */
class NumericValidator extends FieldValidator {

    /**
     * @return $this
     */
    public function check() : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        $success = filter_var($this->value, FILTER_VALIDATE_FLOAT);
        if(false === $success)
            return $this->registerError('invalid_number');

        $this->realValue = floatval($this->value);

        return $this;
    }

    /**
     * @return float|null
     */
    public function getValue() : ?float
    {
        return $this->realValue;
    }

    /**
     * @param float $value
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function greaterThan(float $value, bool $equal = true) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        if(false === filter_var($value, FILTER_VALIDATE_FLOAT))
            throw new \InvalidArgumentException('The value to compare for, must be a number', 511);

        $value = floatval($value);

        if($equal)
        {
            if($this->realValue < $value)
                return $this->registerError('number_grader_and_equal', ['value' => $value]);
        }
        else
        {
            if($this->realValue <= $value)
                return $this->registerError('number_grader', ['value' => $value]);
        }

        return $this;
    }

    /**
     * @param float $value
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function lessThan(float $value, bool $equal = true) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        if(false === filter_var($value, FILTER_VALIDATE_FLOAT))
            throw new \InvalidArgumentException('The value to compare for, must be a number', 511);

        $value = floatval($value);

        if($equal)
        {
            if($this->realValue > $value)
                return $this->registerError('number_less_and_equal', ['value' => $value]);
        }
        else
        {
            if($this->realValue >= $value)
                return $this->registerError('number_less', ['value' => $value]);
        }

        return $this;
    }

    /**
     * @param float $min
     * @param float $max
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function between(float $min, float $max, bool $equal = true) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        if(false === filter_var($min, FILTER_VALIDATE_FLOAT))
            throw new \InvalidArgumentException('The minimum value to compare for, must be a number', 511);

        if(false === filter_var($max, FILTER_VALIDATE_FLOAT))
            throw new \InvalidArgumentException('The maximum value to compare for, must be a number', 511);

        $min = floatval($min);
        $max = floatval($max);

        if($min >= $max)
            throw new \InvalidArgumentException('Invalid range to compare for', 511);

        if($equal)
        {
            if($this->realValue < $min || $this->realValue > $max)
                return $this->registerError('number_between_and_equal', ['min' => $min, 'max' => $max]);
        }
        else
        {
            if($this->realValue <= $min || $this->realValue >= $max)
                return $this->registerError('number_between', ['min' => $min, 'max' => $max]);
        }

        return $this;
    }

    /**
     * @param float $min
     * @param float $max
     * @param bool $notEqual
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function notBetween(float $min, float $max, bool $notEqual = true) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        if(false === filter_var($min, FILTER_VALIDATE_FLOAT))
            throw new \InvalidArgumentException('The minimum value to compare for, must be an number', 511);

        if(false === filter_var($max, FILTER_VALIDATE_FLOAT))
            throw new \InvalidArgumentException('The maximum value to compare for, must be an number', 511);

        $min = floatval($min);
        $max = floatval($max);

        if($min >= $max)
            throw new \InvalidArgumentException('Invalid range to compare for', 511);

        if($notEqual)
        {
            if($this->realValue > $min && $this->realValue < $max)
                return $this->registerError('number_not_between_and_equal', ['min' => $min, 'max' => $max]);
        }
        else
        {
            if($this->realValue >= $min && $this->realValue <= $max)
                return $this->registerError('number_not_between', ['min' => $min, 'max' => $max]);
        }

        return $this;
    }

    /**
     * @param bool $zeroIncluded Indicate if the 0 value can be included
     * @return $this
     */
    public function isPositive(bool $zeroIncluded = false) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        if(!!$zeroIncluded)
        {
            if($this->realValue < 0)
                return $this->registerError('number_positive');
        }
        else
        {
            if($this->realValue <= 0)
                return $this->registerError('number_positive');
        }

        return $this;
    }

    /**
     * @param bool $zeroIncluded Indicate if the 0 value can be included
     * @return $this
     */
    public function isNegative(bool $zeroIncluded = false) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        if(!!$zeroIncluded)
        {
            if($this->realValue > 0)
                return $this->registerError('number_negative');
        }
        else
        {
            if($this->realValue >= 0)
                return $this->registerError('number_negative');
        }

        return $this;
    }
}