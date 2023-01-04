<?PHP

class Crypt
{
	public static string|array $table = 'h410jW56Plkdm79TZ3SfgiQ2bNRec8UOVaXY-';
    public static string $key = '12AB34CD56EF7890';
    //protected static $cipher = MCRYPT_RIJNDAEL_128;
    //protected static $mode = MCRYPT_MODE_CBC;

    public static function encodeUrl(string $str) : string
    {
        $n = strlen($str) * 8 / 5;
        $arr = str_split($str, 1);

        $m = "";
        foreach ($arr as $c) {
            $m .= str_pad(decbin(ord($c)), 8, "0", STR_PAD_LEFT);
        }

        $p = ceil(strlen($m) / 5) * 5;

        $m = str_pad($m, $p, "0", STR_PAD_RIGHT);

        $newstr = "";
        for ($i = 0; $i < $n; $i++) {
            $newstr .= self::$table[bindec(substr($m, $i * 5, 5))];
        }

        return $newstr;
    }

    public static function decodeUrl(string $str) : string
    {
        $n = strlen($str) * 5 / 8;
        $arr = str_split($str, 1);

        $m = "";
        foreach ($arr as $c) {
            $m .= str_pad(decbin(array_search($c, self::$table)), 5, "0", STR_PAD_LEFT);
        }

        $oldstr = "";
        for ($i = 0; $i < floor($n); $i++) {
            $oldstr .= chr(bindec(substr($m, $i * 8, 8)));
        }

        return $oldstr;
    }
	
	public static function getRandomString(int $length)
	{
        try {
            $randomBytes = random_bytes($length);
        }
        catch (Exception $ex) {
            $randomBytes = openssl_random_pseudo_bytes($length);
            if(!$randomBytes)
                $randomBytes = str_shuffle (sha1(uniqid()) . md5(date('c')) );
        }

		return substr(bin2hex($randomBytes), $length);
	}
	

    public static function encrypt($data, $key)
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

    public static function decrypt(string $data, string $key) : string
    {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
}

Crypt::$table = str_split(Crypt::$table, 1);

?>