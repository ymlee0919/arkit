<?PHP

namespace ArkitTest\InputValidation;

use PHPUnit\Framework\TestCase;

class CredentialsDataValidationTest extends ValidationTest
{
    public function userProvider() : array
    {
        return [
            ['username'], ['administrator'], ['JonhDoe01']
        ];
    }

    public function invalidUsersProvider() : array
    {
        return [
            [0], [342], [[23, '@', 'gmail.com']], ['11111111111'], ['aaaaaaaaaaa'], ['http://sample.com'], ['+5434-546.622'], ['Felipe 2'], ['correo@gmail.com']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider userProvider
     */
    public function testUser($user) : void
    {
        $valid = $this->validator->validate('user', $user)->isPersonalData()->isValidUser()->isValid();
        $this->assertTrue($valid, "Invalid user name: {$user}");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider invalidUsersProvider
     */
    public function testInvalidUsers($user) : void
    {
        $valid = $this->validator->validate('user', $user)->isPersonalData()->isValidUser()->isValid();
        $this->assertFalse($valid, "Valid user name: " . json_encode($user));
    }

    public function passwordProvider() : array
    {
        return [
            ['UserName43'], ['AdminTeam34'], ['JonhDoe01']
        ];
    }

    public function invalidPasswordProvider() : array
    {
        return [
            [0], [342], [[23, '@', 'gmail.com']], ['11111111111'], ['aaaaaaaaaaa'], ['http://sample.com'], ['+5434-546.622'], ['correo@gmail.com']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider passwordProvider
     */
    public function testPassword($pass) : void
    {
        $valid = $this->validator->validate('password', $pass)->isPersonalData()->isPassword()->isValid();
        $this->assertTrue($valid, "Invalid password: {$pass}");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider invalidPasswordProvider
     */
    public function testInvalidPassword($pass) : void
    {
        $valid = $this->validator->validate('password', $pass)->isPersonalData()->isPassword()->isValid();
        $this->assertFalse($valid, "Valid password: " . json_encode($pass));
    }

    public function strongPasswordProvider() : array
    {
        return [
            ['UserName*43'], ['AdminTeam.34'], ['Jonh@Doe01'], ['MyBest#is12']
        ];
    }

    public function invalidStrongPasswordProvider() : array
    {
        return [
            [0], [342], [[23, '@', 'gmail.com']], ['password23'] ['11111111111'], ['aaaaaa#aaaaa'], ['http://sample.com'], ['+5434-546.622'], ['correo@gmail.com']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider strongPasswordProvider
     */
    public function testStrongPassword($pass) : void
    {
        $valid = $this->validator->validate('password', $pass)->isPersonalData()->isStrongPassword()->isValid();
        $this->assertTrue($valid, "Invalid strong password: {$pass}");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\PersonalDataValidator
     * @dataProvider invalidPasswordProvider
     */
    public function testInvalidStrongPassword($pass) : void
    {
        $valid = $this->validator->validate('password', $pass)->isPersonalData()->isStrongPassword()->isValid();
        $this->assertFalse($valid, "Valid strong password: " . json_encode($pass));
    }
}