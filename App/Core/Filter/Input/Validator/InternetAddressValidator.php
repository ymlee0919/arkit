<?php

namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;
/**
 * Class InternetAddressValidator
 */
class InternetAddressValidator extends FieldValidator
{

    /**
     * @var string
     */
    private string $urlInfo;

    /**
     * @return $this
     */
    public function check() : self
    {
		return $this;
    }

    /**
     * @return string|null
     */
    public function getValue() : ?string
    {
        return $this->realValue;
    }

    /**
     * @return $this
     */
    public function isIp() : self
	{
        if(!$this->validField)
            return $this;

		if(!filter_var($this->value, FILTER_VALIDATE_IP))
			return $this->registerError('invalid_ip');
        else
            $this->realValue = $this->value;

		return $this;
	}

    /**
     * @return $this
     */
    public function isIpv4() : self
	{
        if(!$this->validField)
            return $this;

		if(!filter_var($this->value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $this->registerError('invalid_ip_v4');
        else
            $this->realValue = $this->value;

		return $this;
	}

    /**
     * @return $this
     */
    public function isIpv6() : self
	{
        if(!$this->validField)
            return $this;

		if(!filter_var($this->value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
            return $this->registerError('invalid_ip_v6');
        else
            $this->realValue = $this->value;

		return $this;
	}

    /**
     * @return $this
     */
    public function isMacAddress() : self
	{
        if(!$this->validField)
            return $this;

        if(!is_string($this->value))
            return $this->registerError('invalid_mac_address');

		if(!preg_match('/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/', $this->value))
            return $this->registerError('invalid_mac_address');
        else
            $this->realValue = $this->value;

		return $this;
	}

    /**
     * @return $this
     */
    public function isValidUrl() : self
	{
        if(!$this->validField)
            return $this;

		if(!filter_var($this->value, FILTER_VALIDATE_URL))
            return $this->registerError('invalid_url');
        else
            $this->realValue = $this->value;

		return $this;
	}

    /**
     * @return $this
     */
    public function isRemoteFile() : self
	{
        // TODO: Implement this function
        return $this;
	}

    /**
     * @param int $min_size
     * @param int $max_size
     * @return $this
     */
    public function remoteFileSize(int $min_size, int $max_size) : self
	{
        // TODO: Implement this function
        return $this;
	}

}