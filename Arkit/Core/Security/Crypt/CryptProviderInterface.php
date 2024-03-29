<?php

namespace Arkit\Core\Security\Crypt;
/**
 * Interface to define a cryptographic provider
 */
interface CryptProviderInterface
{

    /**
     * Generate a pseudo-random string given the length
     * @param int $length
     * @return string
     */
    public function getRandomString(int $length): string;

    /**
     * Make a smooth one way encryption
     *
     * @param string $data String to encrypt
     * @return string String encrypted
     */
    public function oneWaySmoothCrypt(string $data): string;

    /**
     * Make a strong one way encryption
     *
     * @param string $data Data to encrypt
     * @param string|null $key Internal key
     * @return string
     */
    public function oneWayStrongCrypt(string $data, ?string $key = null): string;

    /**
     * Make a smooth two ways encryption. You can get back the previous data using the twoWaySmoothDecrypt function.
     *
     * @param string $data Data to encrypt
     * @return string Internal key
     *
     * @see twoWaysSmoothDecrypt
     */
    public function twoWaysSmoothEncrypt(string $data): string;

    /**
     * Make a smooth two ways decryption. Get the data encrypted by twoWaySmoothEncrypt
     *
     * @param string $data Data to encrypt
     * @return string Internal key
     *
     * @see twoWaysSmoothEncrypt
     */
    public function twoWaysSmoothDecrypt(string $data): string;

    /**
     * Make a strong two ways encryption. You can get back the previous data using the twoWayStrongDecrypt function.
     * @param string $data Data to encrypt
     * @param string $key Internal key
     * @return string
     *
     * @see twoWaysStrongDecrypt
     */
    public function twoWaysStrongEncrypt(string $data, string $key): string;

    /**
     * Make a smooth two ways decryption. Get the data encrypted by twoWayStrongEncrypt, using the same key
     *
     * @param string $data Data to decrypt
     * @param string $key Internal key
     * @return string
     *
     * @see twoWaysStrongEncrypt
     */
    public function twoWaysStrongDecrypt(string $data, string $key): string;

}