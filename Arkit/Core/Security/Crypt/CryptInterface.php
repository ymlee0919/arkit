<?php

namespace Arkit\Core\Security\Crypt;

/**
 * Interface to define a cryptographic provider class
 */
abstract class CryptInterface
{
    /**
     * Default key for strong crypt algorithms
     * @var ?string
     */
    protected ?string $defaultKey;

    /**
     * Class to provide crypt algorithms
     * @var ?CryptProviderInterface
     */
    protected ?CryptProviderInterface $cryptProvider;


    /**
     * Generate a random string given the length
     * 
     * @param int $length String length
     * @return string
     */
    public abstract function getRandomString(int $length): string;

    /**
     * Make a smooth one way encryption
     *
     * @param string $data Data to encrypt
     * @return string String encrypted
     */
    public abstract function smoothCrypt(string $data): string;

    /**
     * Make a strong one way encryption
     *
     * @param string $data Data to encrypt
     * @param string|null $key Key used to encrypt
     * @return string
     */
    public abstract function strongCrypt(string $data, ?string $key = null): string;

    /**
     * Make a smooth two ways encryption. You can get back the previous data using the smoothDecrypt function.
     *
     * @param string $data Data to encrypt
     * @return string
     *
     * @see smoothDecrypt
     */
    public abstract function smoothEncrypt(string $data): string;

    /**
     * Make a smooth two ways decryption. Get the data encrypted by smoothEncrypt
     *
     * @param string $data Data to encrypt
     * @return string
     *
     * @see smoothEncrypt
     */
    public abstract function smoothDecrypt(string $data): string;

    /**
     * Make a strong two ways encryption. You can get back the previous data using the strongDecrypt function.
     * @param string $data Data to encrypt
     * @param string|null $key Base-64 string key
     * @return string
     *
     * @see strongDecrypt
     */
    public abstract function strongEncrypt(string $data, ?string $key = null): string;

    /**
     * Make a smooth two ways decryption. Get the data encrypted by strongEncrypt, using the same key
     *
     * @param string $data Data to decrypt
     * @param string|null $key Base-64 string key
     * @return string
     *
     * @see strongEncrypt
     */
    public abstract function strongDecrypt(string $data, ?string $key = null): string;

}