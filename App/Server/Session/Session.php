<?PHP

/**
 * Class Session
 */
class Session
{
	/**
	 * @var Session|null
	 */
	private static ?Session $instance = null;

	/**
	 * @var string
	 */
	private string $sessionCookieName;

	/**
	 * @var int
	 */
	private int $sessionExpiration;

	/**
	 * @var string
	 */
	private string $sessionCookieDomain;

	/**
	 * @var string
	 */
	private string $sidRegexp;

	/**
	 * @var int
	 */
	private int $sessionTimeToUpdate;

	/**
	 *
	 */
	private function __construct()
	{

	}

	/**
	 * @return Session
	 */
	public static function getInstance() : Session
	{
		if(is_null(self::$instance))
			self::$instance = new Session();

		return self::$instance;
	}

	/**
	 * @param array $config
	 * @return void
	 */
	public function init(array &$config) : void
	{
		$this->sessionCookieName = $config['name'] ?? 'account';
		$this->sessionExpiration = $config['life_time'] ?? 1440;
		$this->sessionTimeToUpdate = $config['time_to_update'] ?? 300;
		$this->sessionCookieDomain = '.' . ($config['domain'] ?? App::$config['domain'] ?? $_SERVER['SERVER_NAME']);

		// Get or set the cookie session name
		if (empty($this->sessionCookieName)) {
			$this->sessionCookieName = ini_get('session.name');
		} else {
			App::$Logs->notice("Session name changed");
			ini_set('session.name', $this->sessionCookieName);
		}

		ini_set('session.use_trans_sid', '0');
		ini_set('session.use_strict_mode', '1');
		ini_set('session.use_cookies', '1');
		ini_set('session.use_only_cookies', '1');
		ini_set('session.cookie_httponly', '1');
		ini_set('session.cache_limiter', 'nocache');
		ini_set('session.cookie_samesite', 'Lax');
		if(!empty($_SERVER['HTTPS']))
			ini_set('session.cookie_secure', '1');

		$cookiesParams = [
			'lifetime' => $this->sessionExpiration,
			'path' => '/' ,
			'domain' => $this->sessionCookieDomain,
			'secure' => !empty($_SERVER['HTTPS']),
			'httponly' => true
		];

		session_set_cookie_params($cookiesParams);

		if (! isset($this->sessionExpiration)) {
			$this->sessionExpiration = (int) ini_get('session.gc_maxlifetime');
		} elseif ($this->sessionExpiration > 0) {
			ini_set('session.gc_maxlifetime', (string) $this->sessionExpiration);
		}

		//Configure the sid length
		self::configureSidLength();
	}

	/**
	 * @return void
	 */
	private function configureSidLength() : void
    {
        $bitsPerCharacter = (int) (ini_get('session.sid_bits_per_character') !== false
            ? ini_get('session.sid_bits_per_character')
            : 5);

        $sidLength = (int) (ini_get('session.sid_length') !== false
            ? ini_get('session.sid_length')
            : 64);

        if (($sidLength * $bitsPerCharacter) < 160) {
            $bits = ($sidLength * $bitsPerCharacter);
            // Add as many more characters as necessary to reach at least 160 bits
            $sidLength += (int) ceil((160 % $bits) / $bitsPerCharacter);
            ini_set('session.sid_length', (string) $sidLength);
        }

		// Yes, 4,5,6 are the only known possible values
		switch ($bitsPerCharacter) {
			case 4:
				$this->sidRegexp = '[0-9a-f]';
				break;

			case 5:
				$this->sidRegexp = '[0-9a-v]';
				break;

			case 6:
				$this->sidRegexp = '[0-9a-zA-Z,-]';
				break;
		}

		$this->sidRegexp .= '{' . $sidLength . '}';
	}

	/**
	* Star the session
	*/
	public function start() : void
	{
		// Check the session is not active
		if (session_status() === PHP_SESSION_ACTIVE) {
			App::$Logs->warning('Session: Sessions is enabled, and one exists.Please don\'t App::$Session->start();');
			return;
		}

		// Sanitize the cookie, because apparently PHP doesn't do that for userspace handlers
		if (isset($_COOKIE[$this->sessionCookieName])
			&& (! is_string($_COOKIE[$this->sessionCookieName]) || ! preg_match('#\A' . $this->sidRegexp . '\z#', $_COOKIE[$this->sessionCookieName]))
		) {
			unset($_COOKIE[$this->sessionCookieName]);
		}

		// Is session ID auto-regeneration configured? (ignoring ajax requests)
		if (
			(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
			&& ($regenerateTime = $this->sessionTimeToUpdate) > 0
		)
		{
			if (! isset($_SESSION['__LAST_REGENERATE'])) {
				$_SESSION['__LAST_REGENERATE'] = time();
			} elseif ($_SESSION['__LAST_REGENERATE'] < (time() - $regenerateTime)) {
				$this->regenerate(true);
			}
		}
		// Another work-around ... PHP doesn't seem to send the session cookie
		// unless it is being currently created or regenerated
		elseif (isset($_COOKIE[$this->sessionCookieName]) && $_COOKIE[$this->sessionCookieName] === session_id())
		{
			setcookie($this->sessionCookieName, session_id(), [
				'expires'  	=> $this->sessionExpiration === 0 ? 0 : time() + $this->sessionExpiration,
				'path'     	=> '/',
				'domain' 	=> $this->sessionCookieDomain,
				'secure' 	=> empty($_SERVER['HTTPS']),
				'httponly' 	=> true, // for security
				'samesite' 	=> 'Lax',
				'raw'      	=> false,
			]);
		}

		session_start();

		// Init vars
		if(!isset($_SESSION['__VARS']))
		{
			$_SESSION['__VARS'] = array();
			$_SESSION['FingerPrint'] = hash('snefru256', $_SERVER['HTTP_USER_AGENT']);
		}
		else
		{
			// If not set the fingerprint, destroy the current session
			if(!isset($_SESSION['FingerPrint']) || $_SESSION['FingerPrint'] != hash('snefru256', $_SERVER['HTTP_USER_AGENT']))
			{
				$sId = session_id();
				$this->destroy();
				App::$Logs->warning('FingerPrint session mismatch, deleted session: ' . $sId);
				
				session_start();
				return;
			}
			$keys = array_keys($_SESSION);
			foreach($keys as $key)
			{
                if(isset($_SESSION['__VARS'][$key]))
                {
                    if($_SESSION['__VARS'][$key] == '_OLD')
                    {
                        unset($_SESSION[$key]);
                        unset($_SESSION['__VARS'][$key]);
                    }
                    elseif($_SESSION['__VARS'][$key] == '_NEW')
                        $_SESSION['__VARS'][$key] = '_OLD';
                }
			}
		}
	}

	/**
	 * @return string
	 */
	public function getCryptKey() : string
    {
        if(!isset($_SESSION['CRYPT_KEY']))
            $_SESSION['CRYPT_KEY'] = base64_encode(openssl_random_pseudo_bytes(32));

        return $_SESSION['CRYPT_KEY'];
    }

	/**
	* Get a session var given the key
	* @param string $key
	* @return mixed
	*/
	public function get(string $key) : mixed
	{
		if(strtoupper($key) == 'ID')
			return session_id();

		if(isset($_SESSION[$key])) return $_SESSION[$key];
        return null;
	}

	/**
	* Set a session var
	* @param string $key Key to search for. The key ID is not allowed
	* @param mixed $value Value to store
	 *
	*/
	public function set(string $key, mixed $value) : void
	{
		$_SESSION[$key] = $value;
	}
	
	/**
	* Remove a session var
	* @param string $key Key var to remove
	*/
	public function remove(string $key) : void
	{
		if(isset($_SESSION[$key])) unset($_SESSION[$key]);
		if(isset($_SESSION['__VARS'][$key])) unset($_SESSION['__VARS'][$key]);
	}

	/**
	 * Set a var session like flash, they will persist until the next call, then
	 * they will be deleted
	 * @param string $key Key
	 * @param mixed $value Value to store
	 */
	public function set_flash(string $key, mixed $value) : void
	{
		$_SESSION[$key] = $value;
		$_SESSION['__VARS'][$key] = '_NEW';
	}

	/**
	 * Make a stored var as flash
	 * @param string $key
	 */
	public function make_flash(string $key) : void
	{
		if(isset($_SESSION[$key]))
			$_SESSION['__VARS'][$key] = '_NEW';
	}
	
	/**
	* Get a session value given the key and remove it
	* @param string $key
     * @return mixed|null
	*/
	public function pop(string $key) : mixed
	{
		if(isset($_SESSION[$key]))
		{
			$value = $_SESSION[$key];
			
			unset($_SESSION[$key]);
			if(isset($_SESSION['__VARS'][$key])) unset($_SESSION['__VARS'][$key]);
			
			return $value;
		}
		return null;
	}

    /**
     * @param bool $removeOld
     */
    public function regenerate(bool $removeOld = false) : void
	{
		$_SESSION['__LAST_REGENERATE'] = time();
		session_regenerate_id($removeOld);
	}
	
	public function destroy() : void
	{
		// Destroy the session
		session_destroy();
		// Remove the cookie
		setcookie($this->sessionCookieName, $sId, [
			'expires'  	=> time() - 7200,
			'path'     	=> '/',
			'domain' 	=> $this->sessionCookieDomain,
			'secure' 	=> empty($_SERVER['HTTPS']),
			'httponly' 	=> true, // for security
			'samesite' 	=> 'Lax',
			'raw'      	=> false,
		]);
	}


    /**
     * @param array $options
     */
    public function load(array $options) : void
	{
		session_start($options);
	}

    /**
     * @return string
     */
    public function id() : string
	{
		return session_id();
	}

    /**
     * @param string $key
     * @return bool
     */
    public function is_set(string $key) : bool
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function __get(string $key) : mixed
	{
		if(strtoupper($key) == 'ID')
			return session_id();

		if(isset($_SESSION[$key]))
			return $_SESSION[$key];
	}

	/**
	 * @param string $key Key to search for. The key ID is not allowed
	 * @param mixed $value Associated value
	 * @return void
	 */
	public function __set(string $key, mixed $value) : void
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function __isset(string $key) : bool
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * @param string $key
	 * @return void
	 */
	public function __unset(string $key) : void
	{
		if(isset($_SESSION[$key])) unset($_SESSION[$key]);
		if(isset($_SESSION['__VARS'][$key])) unset($_SESSION['__VARS'][$key]);
	}
}

?>
