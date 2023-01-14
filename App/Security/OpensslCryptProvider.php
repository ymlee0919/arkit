<?php

/**
 *
 */
class OpensslCryptProvider implements CryptProviderInterface
{

    /**
     * @var string
     */
    protected string $hashAlgo;

    /**
     * @var string
     */
    protected string $smoothCryptString = 'MbQ5pZCL2Dh3yJEfSwq1co-KHa7ixTWn0Rg'; // Shuffle text from: abcfghinopqwxyCDEHJKLMQRSTWZ-012357

    /**
     * @var string[]
     */
    protected array $smoothCryptArray;

    /**
     * @param string $hashAlgo
     */
    public function __construct(string $hashAlgo)
    {
        $this->hashAlgo = $hashAlgo;
        $this->smoothCryptArray = str_split($this->smoothCryptString, 1);
    }

    /**
     * @inheritDoc
     */
    public function getRandomString(int $length) : string
    {
        return openssl_random_pseudo_bytes($length);
    }

    /**
     * @inheritDoc
     */
    public function oneWaySmoothCrypt(string $data): string
    {
        return hash($this->hashAlgo, $data);
    }

    /**
     * @inheritDoc
     */
    public function oneWayStrongCrypt(string $data, ?string $key = null): string
    {
        $hmacKey = $key ?? substr(sha1($data), 2, 12);
        $hmacKey .= '#' . strrev($hmacKey);
        return hash_hmac($this->hashAlgo, $data, $hmacKey);
    }

    /**
     * @inheritDoc
     */
    public function twoWaysSmoothEncrypt(string $data): string
    {
        $n = strlen($data) * 8 / 5;
        $arr = str_split($data, 1);

        $m = "";
        foreach ($arr as $c) {
            $m .= str_pad(decbin(ord($c)), 8, "0", STR_PAD_LEFT);
        }

        $p = ceil(strlen($m) / 5) * 5;

        $m = str_pad($m, $p, "0", STR_PAD_RIGHT);

        $newstr = "";
        for ($i = 0; $i < $n; $i++) {
            $newstr .= $this->smoothCryptArray[bindec(substr($m, $i * 5, 5))];
        }

        return $newstr;
    }

    /**
     * @inheritDoc
     */
    public function twoWaysSmoothDecrypt(string $data): string
    {
        $n = strlen($data) * 5 / 8;
        $arr = str_split($data, 1);

        $m = "";
        foreach ($arr as $c) {
            $m .= str_pad(decbin(array_search($c, $this->smoothCryptArray)), 5, "0", STR_PAD_LEFT);
        }

        $oldstr = "";
        for ($i = 0; $i < floor($n); $i++) {
            $oldstr .= chr(bindec(substr($m, $i * 8, 8)));
        }

        return $oldstr;
    }

    /**
     * @inheritDoc
     */
    public function twoWaysStrongEncrypt(string $data, string $key): string
    {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return base64_encode($encrypted . '::' . $iv);
    }

    /**
     * @inheritDoc
     */
    public function twoWaysStrongDecrypt(string $data, string $key): string
    {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
}