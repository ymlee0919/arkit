<?php

namespace Arkit\Core\Filter\Input\Protection;

use \Arkit\App;
use \Firebase\JWT\JWT;
use \Arkit\Core\Filter\Input\Exception;

/**
 * This class handle the JWT token.
 */
class JWTHandler
{
    private string $algorithm;

    private string $secretKey;

    private string $type;

    private string $fieldName;

    private int $expire;


    /**
     * Constructor of the class
     */
    public function __construct()
    {
        $this->secretKey = App::$Env['JWT_SECRET_KEY'];
    }

    /**
     * Initialize the handler
     *
     * @param array $config
     * @return void
     */
    public function init(array &$config) : void
    {
        $this->algorithm = $config['algorithm'] ?? 'HS256';
        $this->type = $config['type'] ?? 'cookie';
        $this->fieldName = $config['field_name'] ?? 'auth';
        $this->expire = $config['expire'] ?? 7200;
    }

    /**
     * Generate the JWT
     *
     * @param array $payload Payload
     * @return string
     */
    public function generateJWT(array $payload) : string
    {
        $request = App::$Request;

        // Add issused and expiration time
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->expire;

        $payload['iat'] = $issuedAt;
        $payload['exp'] = $expirationTime;
        
        // Set Issuer and Subject Claims
        $payload['iss'] = $_SERVER['SERVER_NAME'];
        
        $origin = $request->getHeader('Origin');
        if(!empty($origin))
            $payload['sub'] = $_SERVER['SERVER_NAME'];

        // Build a KeyId
        //$keyId = JWT::sign($this->secretKey, App::$Crypt->smoothEncrypt($this->secretKey), 'ES384');
        //$keyId = App::$Crypt->smoothEncrypt($this->secretKey);
        
        $jwt = JWT::encode($payload, $this->secretKey, $this->algorithm);

        switch($this->type)
        {
            # Put into cookies
            case 'cookie':
                $cookieName  = $this->fieldName;
                $cookieValue =  $jwt;
                $expiry = $_SERVER['REQUEST_TIME'] + $this->expire;
                $domain = $_SERVER['SERVER_NAME'];
                $secure = (!empty($_SERVER['HTTPS']));
                $path = '/';

                $cookie = \Arkit\Core\Persistence\Client\Cookie::build($cookieName, $cookieValue, $expiry, $path, $domain, $secure, true, \Arkit\Core\Persistence\Client\CookieInterface::SAMESITE_STRICT);
                App::$Response->getCookies()->put($cookie);
                break;

            # Put into the header
            case 'header':
                App::$Response->setHeader($this->fieldName, $jwt);
                break;
            
            # Set as parameter
            case 'parameter':
                App::$Response->assign($this->fieldName, $jwt);
                break;
        }

        return $jwt;
    }

    /**
     * Decode the JWT
     *
     * @return array|null Array of values. Null on error.
     */
    public function decodeJWT() : ?array
    {
        $jwt = null;

        // Get the Token given the source
        switch($this->type)
        {
            case 'cookie':
                $cookie = App::$Request->getCookies()->get($this->fieldName);
                if(!!$cookie)
                    $jwt = $cookie->getValue();
                break;

            # Put into the header
            case 'header':
                $jwt = App::$Request->getHeader($this->fieldName);
                break;
            
            # Set as parameter
            case 'parameter':
                $jwt = App::$Request->getPostParam($this->fieldName);
                break;
        }

        
        if(!$jwt)
            return null;
        
        try
        {
            $key = new \Firebase\JWT\Key($this->secretKey, $this->algorithm);
            $payload = (array) JWT::decode($jwt, $key);
        } 
        catch(\Firebase\JWT\ExpiredException $ex)
        {
            throw new Exception\ExpiredCodeException('JWT expired');
        }
        catch(\Exception $ex)
        {
            throw new Exception\InvalidCodeException('Invalid JWT');
        }
            
        return $payload;
    }

    /**
     * Relase the JWT
     *
     * @return void
     */
    public function release() : void
    {
        switch($this->type)
        {
            # Store into cookies
            case 'coockie':
                $cookie = App::$Request->getCookies()->get($this->fieldName);
                if(!$cookie)
                    return;
                
                $cookie->setExpired()->dispatch();
                break;
            
            # Put into the header
            case 'header':
                break;
            
            # Set as parameter
            case 'parameter':
                break;
        }
    }

}