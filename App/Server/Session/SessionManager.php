<?PHP

/**
 * Class Session
 */
class Session
{
	/**
	* Star the session
	*/
	public static function start() : void
	{
		if(session_id() == '')		
			session_start();
		if(!isset($_SESSION['__VARS']))
			$_SESSION['__VARS'] = array();
		else
		{
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

    public static function getCryptKey() : string
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
	public static function get(string $key) : mixed
	{
		if(isset($_SESSION[$key])) return $_SESSION[$key];
        return null;
	}

	/**
	* Set a session var
	* @param string $key Key
	* @param mixed $value Value to store
	*/
	public static function set(string $key, mixed $value) : void
	{
		$_SESSION[$key] = $value;
	}
	
	/**
	* Remove a session var
	* @param string $key Key var to remove
	*/
	public static function remove(string $key) : void
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
    public static function set_flash(string $key, mixed $value) : void
    {
        $_SESSION[$key] = $value;
        $_SESSION['__VARS'][$key] = '_NEW';
    }
    
    /**
	* Make a stored var as flash
	* @param string $key
	*/
    public static function make_flash(string $key) : void
    {
		if(isset($_SESSION[$key])) 
			$_SESSION['__VARS'][$key] = '_NEW';
	}
	
	/**
	* Get a session value given the key and remove it
	* @param string $key
     * @return mixed|null
	*/
	public static function pop(string $key) : mixed
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
    public static function regenerate(bool $removeOld = false) : void
	{
        if(!!$removeOld)
            session_destroy();

        session_start();
		session_regenerate_id($removeOld);
        self::start();
	}

    /**
     * @param array $options
     */
    public static function load(array $options) : void
	{
		session_start($options);
	}

    /**
     * @return string
     */
    public static function id() : string
	{
		return session_id();
	}

    /**
     * @param string $key
     * @return bool
     */
    public static function is_set(string $key) : bool
	{
		return isset($_SESSION[$key]);
	}
}

?>
