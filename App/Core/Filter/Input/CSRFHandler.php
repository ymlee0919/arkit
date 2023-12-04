<?php

namespace Arkit\Core\Filter\Input;

use \Arkit\App;
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
     *
     */
    public function __construct()
    {
        // Please, change this random string for each website application
        $this->csrf_key = '{06^AFxd=?tpKHWq#}';
    }

    /**
     * @param array $config
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
     * @param string $formId
     * @param int|null $expire
     * @return string
     */
    public function generateCode(string $formId, ?int $expire = null) : string
    {
        $expiry = $_SERVER['REQUEST_TIME'] + ($expire ?? $this->defaultExpire);
        $code = App::$Session['CSRF']. '|' . strval( $expiry ) . '|' . trim(md5( $this->csrf_key .'['. $formId.']'));
        return App::$Crypt->strongEncrypt($code, App::$Session['PRIVATE_KEY']);
    }

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
     * @param string $formId
     * @param string $code
     * @return string
     */
    public function validateCode(string $formId, string $code) : string
    {
        $token = App::$Crypt->strongDecrypt($code, App::$Session['PRIVATE_KEY']);
        if(!$token)
            return self::CSRF_VALIDATION_INVALID;

        $parts = explode('|', $token);
        if(count($parts) != 3)
            return self::CSRF_VALIDATION_INVALID;

        if(trim($parts[0]) != App::$Session['CSRF']) return self::CSRF_VALIDATION_INVALID;
        if(intval($parts[1]) < $_SERVER['REQUEST_TIME'] ) return self::CSRF_VALIDATION_EXPIRED;
        if(trim($parts[2]) != md5( $this->csrf_key .'['. $formId.']')) return self::CSRF_VALIDATION_INVALID;

        return self::CSRF_VALIDATION_SUCCESS;
    }

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