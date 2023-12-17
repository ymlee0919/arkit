<?php

namespace Arkit\Core\Filter\Input\Protection;

use \Arkit\App;
use \Arkit\Core\Filter\Input\Exception;

/**
 * This class handle the CSRF token.
 *  - Generate and validate a hidden input code given a Form ID and expiration time
 *  - Generate and validate a cookie given a Form ID.
 *     The cookie name is associated with the Form ID. So, the unique who know the expected cookie is the server
 *     The cookie value is associated with the Session and the Form ID.
 */
class CSRFHandler
{
    /**
     * For invalid CSRF code
     */
    const CSRF_VALIDATION_INVALID = 'INVALID';

    /**
     * For expired CSRF code
     */
    const CSRF_VALIDATION_EXPIRED = 'EXPIRED';

    /**
     * For valid code
     */
    const CSRF_VALIDATION_SUCCESS = 'SUCCESS';

    /**
     * @var int
     */
    private int $defaultExpire;

    /**
     * @var string
     */
    private string $csrf_key;

    /**
     * @var string
     */
    private string $cookie_prefix;

    /**
     * Constructor of the class
     */
    public function __construct()
    {
        // Please, change this random string for each website application
        $this->csrf_key = '{06^AFxd=?tpKHWq#}';
    }

    /**
     * Init the Csrf Handler
     * 
     * @param array $config Configuration array
     * 
     * @return void
     */
    public function init(array &$config): void
    {
        $this->defaultExpire    = $config['expire'] ?? 7200;
        $this->cookie_prefix    = $config['cookie_prefix'] ?? 'field_';

        if(!isset(App::$Session['CSRF']))
            App::$Session['CSRF'] = App::$Crypt->getRandomString($config['private_key_length'] ?? 32);

        if(!isset(App::$Session['PRIVATE_KEY']))
            App::$Session['PRIVATE_KEY'] = base64_encode(str_shuffle(App::$Crypt->getRandomString(64)));
    }

    /**
     * Generate a CSRF Code
     * 
     * @param string $formId Form Id
     * @param int|null $expire (Optional) Expiry time
     * @return string
     */
    public function generateCode(string $formId, ?int $expire = null) : string
    {
        $expiry = $_SERVER['REQUEST_TIME'] + ($expire ?? $this->defaultExpire);
        $code = App::$Session['CSRF']. '|' . strval( $expiry ) . '|' . trim(md5( $this->csrf_key .'['. $formId.']'));
        return App::$Crypt->strongEncrypt($code, App::$Session['PRIVATE_KEY']);
    }

    /**
     * Generate cookies associated to a given form Id
     *
     * @param string $formId Form id
     * @param integer|null $expire Expiry time
     * @param string $path Cookie path
     * @return void
     */
    public function generateCookie(string $formId, ?int $expire = null, string $path = '/') : void
    {
        $cookieName  = $this->cookie_prefix . $this->getCryptFormName($formId);
        $cookieValue =  $this->buildCookieValue($formId);
        $expiry = $_SERVER['REQUEST_TIME'] + ($expire ?? $this->defaultExpire);
        $domain = $_SERVER['SERVER_NAME'];
        $secure = (!empty($_SERVER['HTTPS']));

        $cookie = \Arkit\Core\Persistence\Client\Cookie::build($cookieName, $cookieValue, $expiry, $path, $domain, $secure, true, \Arkit\Core\Persistence\Client\CookieInterface::SAMESITE_STRICT);
        App::$Response->getCookies()->put($cookie);
    }

    /**
     * Validate a CRSF code
     * 
     * @param string $formId Form id
     * @param string $code CSRF Code
     * 
     * @return bool True if validatio success
     * 
     * @throws Exception\InvalidCodeException
     * @throws Exception\ExpiredCodeException
     */
    public function validateCode(string $formId, string $code) : string
    {
        $token = App::$Crypt->strongDecrypt($code, App::$Session['PRIVATE_KEY']);
        if(!$token)
            throw new Exception\InvalidCodeException('Invalid CSRF code');

        $parts = explode('|', $token);
        if(count($parts) != 3)
            throw new Exception\InvalidCodeException('Invalid CSRF code');

        if(trim($parts[0]) != App::$Session['CSRF']) 
            throw new Exception\InvalidCodeException('Invalid CSRF code');

        if(intval($parts[1]) < $_SERVER['REQUEST_TIME'] )
            throw new Exception\ExpiredCodeException('CSRF code expired');

        if(trim($parts[2]) != md5( $this->csrf_key .'['. $formId.']'))
            throw new Exception\InvalidCodeException('Invalid CSRF code');

        return self::CSRF_VALIDATION_SUCCESS;
    }

    /**
     * Validate a cookie
     *
     * @param string $formId Form Id
     * @return boolean Indicate if the cookie is valid or not
     */
    public function validateCookie(string $formId) : bool
    {
        $cookieName  = $this->cookie_prefix . $this->getCryptFormName($formId);
        $cookieValue =  $this->buildCookieValue($formId);

        $cookie = App::$Request->getCookies()->get($cookieName);
        if(is_null($cookie))
            return false;

        if($cookie->getValue() != $cookieValue)
            return false;

        return true;
    }

    /**
     * Release cookie from navigator
     *
     * @param string $formId Form id
     * 
     * @return void
     */
    public function releaseCookie(string $formId) : void
    {
        $cookieName  = $this->cookie_prefix . $this->getCryptFormName($formId);
        $csrfCookie = App::$Request->getCookies()->get($cookieName);
        if(!!$csrfCookie)
            $csrfCookie->setValue('')->setExpired()->dispatch();
    }

    /**
     * @param string $formId
     * @return string
     */
    private function getCryptFormName(string $formId) : string
    {
        return App::$Crypt->smoothEncrypt($formId);
    }

    /**
     * @param string $formId
     * @return string
     */
    private function buildCookieValue(string $formId) : string
    {
        return App::$Crypt->smoothEncrypt($this->csrf_key . '[[' . $formId . ']]');
    }


}