<?php
namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;
use Arkit\Core\Filter\InputValidator;

/**
 * Class DateValidator
 */
class DateValidator extends FieldValidator {

    /**
     * @var string
     */
    private string $str_value;

    /**
     * @var string
     */
    private string $format;


    /**
     * @param $format string
     */
    public function setFormat(string $format) : void
    {
        $this->format = $format;
    }

    /**
     * @return \DateTime|null
     */
    public function getValue() : ?\DateTime
    {
        return $this->realValue;
    }

    /**
     * @param string $date
     * @return bool|string
     */
    private function toStr(string $date) : bool|string
    {
        $parts = date_parse_from_format($this->format, $date);

        if($parts['warning_count'] > 0 || $parts['error_count'] > 0)
            return false;

        $str = sprintf('%s.%s.%s',
            $parts['year'],
            str_pad($parts['month'], 2, '0', STR_PAD_LEFT),
            str_pad($parts['day'], 2, '0', STR_PAD_LEFT)
        );

        return $str;
    }

    /**
     * @return $this
     */
    public function check() : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        $str_val = null;

        if(is_string($this->value))
            $str_val = $this->toStr($this->value);
        elseif(is_object($this->value) && get_class($this->value) == 'DateTime')
            $str_val = $this->value->format('Y.m.d');

        if(empty($str_val))
            return $this->registerError('invalid_date');

        $this->str_value = $str_val;
        $this->realValue = date_create_from_format('Y.m.d', $this->str_value);

        return $this;
    }

    /**
     * @param string|\DateTime $value
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function isBefore(string|\DateTime $value, bool $equal = true) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        $str_val = null;

        if(is_string($value))
            $str_val = $this->toStr($value);
        elseif(is_object($value) && get_class($value) == 'DateTime')
            $str_val = $value->format('Y.m.d');

        if(!$str_val)
            throw new \InvalidArgumentException('The value to compare for, must be a valid date', 511);

        $cmp = strcmp($str_val, $this->str_value);
        // TODO: The translation of the date when register the error
        if($equal)
        {
            if($cmp > 0)
                return $this->registerError('date_before_and_equal', ['value' => $value]);
        }
        else
        {
            if($cmp >= 0)
                return $this->registerError('date_before', ['value' => $value]);
        }

        return $this;
    }

    /**
     * @param string|\DateTime $value
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function isAfter(string|\DateTime $value, bool $equal = true) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        $str_val = null;

        if(is_string($value))
            $str_val = $this->toStr($value);
        elseif(is_object($value) && get_class($value) == 'DateTime')
            $str_val = $value->format('Y.m.d');

        if(!$str_val)
            throw new \InvalidArgumentException('The value to compare for, must be a valid date', 511);

        $cmp = strcmp($this->str_value, $str_val);
        // TODO: The translation of the date when register the error
        if($equal)
        {
            if($cmp > 0)
                return $this->registerError('date_after_and_equal', ['value' => $value]);
        }
        else
        {
            if($cmp >= 0)
                return $this->registerError('date_after', ['value' => $value]);
        }

        return $this;
    }

    /**
     * @param string|\DateTime $min
     * @param string|\DateTime $max
     * @param bool $equal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function between(string|\DateTime $min, string|\DateTime $max, bool $equal = true) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        // Validate min value
        $min_val = null;

        if(is_string($min))
            $min_val = $this->toStr($min);
        elseif(is_object($min) && get_class($min) == 'DateTime')
            $min_val = $min->format('Y.m.d');

        if(!$min_val)
            throw new \InvalidArgumentException('The minimum value to compare for, must be a date', 511);

        // Validate max value
        $max_val = null;

        if(is_string($max))
            $max_val = $this->toStr($max);
        elseif(is_object($max) && get_class($max) == 'DateTime')
            $max_val = $min->format('Y.m.d');

        if(!$max_val)
            throw new \InvalidArgumentException('The maximum value to compare for, must be a date', 511);

        if($equal)
        {
            if($this->str_value < $min_val || $this->str_value > $max_val)
                return $this->registerError('date_between_and_equal', ['min' => $min, 'max' => $max]);
        }
        else
        {
            if($this->str_value <= $min_val || $this->str_value >= $max_val)
                return $this->registerError('date_between', ['min' => $min, 'max' => $max]);
        }

        return $this;
    }

    /**
     * @param string|\DateTime $min
     * @param string|\DateTime $max
     * @param bool $notEqual
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function notBetween(string|\DateTime $min, string|\DateTime $max, bool $notEqual = true) : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;

        // Validate min value
        $min_val = null;

        if(is_string($min))
            $min_val = $this->toStr($min);
        elseif(is_object($min) && get_class($min) == 'DateTime')
            $min_val = $min->format('Y.m.d');

        if(!$min_val)
            throw new \InvalidArgumentException('The minimum value to compare for, must be a date', 511);

        // Validate max value
        $max_val = null;

        if(is_string($max))
            $max_val = $this->toStr($max);
        elseif(is_object($max) && get_class($max) == 'DateTime')
            $max_val = $min->format('Y.m.d');

        if(!$max_val)
            throw new \InvalidArgumentException('The maximum value to compare for, must be a date', 511);

        if($notEqual)
        {
            if($this->str_value >= $min_val && $this->str_value <= $max_val)
                return $this->registerError('date_not_between_and_equal', ['min' => $min, 'max' => $max]);
        }
        else
        {
            if($this->str_value > $min_val && $this->str_value < $max_val)
                return $this->registerError('date_not_between', ['min' => $min, 'max' => $max]);
        }

        return $this;
    }
}