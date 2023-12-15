<?PHP
namespace Arkit\Core\Exception;

/**
 * General class for any invalid action exception
 */
class InvalidActionException extends \Exception
{
    /**
     * Constructor of the exception
     *
     * @param string $message Exception message
     * @param integer $code (Optional) Error code
     */
    public function __construct(string $message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * ToString method
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . " :: [{$this->code}]: {$this->message}";
    }
}