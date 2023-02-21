<?PHP

namespace ArkitTest\InputValidation;

use PHPUnit\Framework\TestCase;

class PersonalDataValidationTest extends ValidationTest
{

    public function emailProvider() : array
    {
        // Build source
        $source = dirname(__DIR__) . '/files/emails.list';
        // Read file
        $lines = file($source, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        // Set each email into an array
        $result = [];
        foreach($lines as $email)
            $result[] = [$email];

        return $result;
    }

    public function invalidEmailProvider() : array
    {
        return [
            [0], [342], [[23, '@', 'gmail.com']], ['micorreo'], ['http://sample.com'], ['5434-546622']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider emailProvider
     */
    public function testEmail($email) : void
    {
        $valid = $this->validator->validate('email', $email)->isPersonalData()->isEmail()->isValid();
        $this->assertTrue($valid, "Invalid email: {$email}");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider invalidEmailProvider
     */
    public function testInvalidEmail($email) : void
    {
        $valid = $this->validator->validate('email', $email)->isPersonalData()->isEmail()->isValid();
        $this->assertFalse($valid, "Valid email " . json_encode($email));
    }

    public function emailListProvider() : array
    {
        // Build source
        $source = dirname(__DIR__) . '/files/emails.list';
        // Read file
        $lines = file($source, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        // Set each email into an array
        $result = [];
        $list = [];
        $maxLength = rand(3, 5);
        foreach($lines as $email)
        {
            $list[] = $email;
            if(count($list) == $maxLength)
            {
                $result[] = [ implode(';', $list) ];
                $list = [];
                $maxLength = rand(3, 5);
            }
        }

        return $result;
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider emailListProvider
     */
    public function testEmailList($emailList) : void
    {
        $valid = $this->validator->validate('list', $emailList)->isPersonalData()->isEmailList()->isValid();
        $this->assertTrue($valid, "Invalid email list: {$emailList}");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider invalidEmailProvider
     */
    public function testInvalidEmailList($emailList) : void
    {
        $valid = $this->validator->validate('list', $emailList)->isPersonalData()->isEmailList()->isValid();
        $this->assertFalse($valid, "Valid email list:" . json_encode($emailList));
    }

    public function manesProvider() : array
    {
        // Build source
        $source = dirname(__DIR__) . '/files/names.list';
        // Read file
        $lines = file($source, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        // Set each email into an array
        $result = [];
        foreach($lines as $name)
            $result[] = [$name];

        return $result;
    }

    public function invalidNamesProvider() : array
    {
        return [
            [0], [342], [[23, '@', 'gmail.com']], ['micorreo'], ['http://sample.com'], ['5434-546622'], ['Felipe 2']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider manesProvider
     */
    public function testName($name) : void
    {
        $valid = $this->validator->validate('name', $name)->isPersonalData()->isValidName()->isValid();
        $this->assertTrue($valid, "Invalid personal name: {$name}");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider invalidNamesProvider
     */
    public function testInvalidNames($name) : void
    {
        $valid = $this->validator->validate('name', $name)->isPersonalData()->isValidName()->isValid();
        $this->assertFalse($valid, "Valid personal name: " . json_encode($name));
    }

    public function phonesProvider() : array
    {
        // Build source
        $source = dirname(__DIR__) . '/files/phones.list';
        // Read file
        $lines = file($source, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        // Set each email into an array
        $result = [];
        foreach($lines as $name)
            $result[] = [$name];

        return $result;
    }

    public function invalidPhonesProvider() : array
    {
        return [
            [0], [342], [[23, '@', 'gmail.com']], ['micorreo'], ['http://sample.com'], ['+5434+546.622'], ['Felipe 2']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider phonesProvider
     */
    public function testPhone($phone) : void
    {
        /*
        Pending....
        (+34) 617 279 035
        (+34) 617 27 9064
        (+34) 681-168-236
        (+34) 431-16-8221
        */
        $valid = $this->validator->validate('phone', $phone)->isPersonalData()->isPhone()->isValid();
        $this->assertTrue($valid, "Invalid phone number: {$phone}");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider invalidPhonesProvider
     */
    public function testInvalidPhones($phone) : void
    {
        $valid = $this->validator->validate('phone', $phone)->isPersonalData()->isPhone()->isValid();
        $this->assertFalse($valid, "Valid phone number: " . json_encode($phone));
    }

}