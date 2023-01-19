<?php

namespace Arkit\Core\Security;

use \Arkit\Core\Security\Crypt\CryptInterface;
use \Arkit\Core\Security\Crypt\CryptProviderInterface;

/**
 * Class for handle cryptography
 */
class Crypt extends CryptInterface
{

    /**
     * @var string|null
     */
    protected ?string $hashAlgo;

    /**
     * Constructor of the class
     */
    public function __construct()
    {
        $this->defaultKey = null;
        $this->cryptProvider = null;
        $this->hashAlgo = null;
    }

    /**
     * @param array $config
     * @return void
     */
    public function init(array &$config = []): void
    {
        $this->defaultKey = base64_encode($config['default_key'] ?? 'Ax09#qpsk&FEC=3/4K');
        $this->hashAlgo = $config['hash_algo'] ?? 'gost-crypto';

        \Loader::import('OpensslCryptProvider', 'App.Security.OpensslCryptProvider');
        $this->cryptProvider = new \Arkit\Core\Security\Crypt\OpensslCryptProvider($this->hashAlgo);
    }

    /**
     * @param CryptProviderInterface $cryptProvider
     * @return void
     */
    public function setCryptProvider(CryptProviderInterface $cryptProvider)
    {
        $this->cryptProvider = $cryptProvider;
    }

    /**
     * @inheritDoc
     */
    public function getRandomString(int $length): string
    {
        $randomBytes = $this->cryptProvider->getRandomString($length);
        return bin2hex($randomBytes);
    }

    /**
     * @inheritDoc
     */
    public function smoothCrypt(string $data): string
    {
        return $this->cryptProvider->oneWaySmoothCrypt($data);
    }

    /**
     * @inheritDoc
     */
    public function strongCrypt(string $data, ?string $key = null): string
    {
        $this->cryptProvider->oneWayStrongCrypt($data, $key ?? $this->defaultKey);
    }

    /**
     * @inheritDoc
     */
    public function smoothEncrypt(string $data): string
    {
        return $this->cryptProvider->twoWaysSmoothEncrypt($data);
    }

    /**
     * @inheritDoc
     */
    public function smoothDecrypt(string $data): string
    {
        return $this->cryptProvider->twoWaysSmoothDecrypt($data);
    }

    /**
     * @inheritDoc
     */
    public function strongEncrypt(string $data, ?string $key = null): string
    {
        return $this->cryptProvider->twoWaysStrongEncrypt($data, $key ?? $this->defaultKey);
    }

    /**
     * @inheritDoc
     */
    public function strongDecrypt(string $data, ?string $key = null): string
    {
        return $this->cryptProvider->twoWaysStrongDecrypt($data, $key ?? $this->defaultKey);
    }
}