<?php

namespace ArkitTest\InputValidation;

class InternetAddressValidationTest extends ValidationTest
{
    public function validIpV4Provider() : array
    {
        return [
            ['192.158.10.11'],
            ['34.215.126.205'],
            ['214.70.222.244'],
            ['233.70.63.37'],
            ['211.58.39.248'],
            ['254.35.139.75'],
            ['215.166.236.34'],
            ['10.144.48.214'],
            ['254.130.19.229'],
            ['83.13.252.82'],
            ['171.91.147.251']
        ];

    }

    public function validIpV6Provider() : array
    {
        return [
            ['3e45:3fc0:9c82:4663:2a78:9b3c:1c22:5b5e'],
            ['2025:6466:4280:861c:cb81:992c:a51d:3f48'],
            ['84ab:68fb:cd47:9bbc:b478:2846:351c:3a53'],
            ['d31a:a11f:d466:b72a:ab53:ecae:329c:67f7'],
            ['0f85:3698:4fe0:4669:88e3:a96a:5ad7:7f65'],
            ['8ddb:3352:8fdc:4016:059d:6ebc:39c6:4789'],
            ['947c:ca41:68db:8322:9b1f:072f:18c7:2f7c'],
            ['8810:62a5:084b:d0ad:cb3a:9bfd:7842:383b'],
            ['37d3:d25e:3f0c:5064:3de0:2b87:ab40:2805'],
            ['63f5:6a06:6185:72c5:e9ce:1954:92ac:f05f'],
            ['2345:0425:2CA1::0567:5673:23b5'],
            ['2345:0425:2CA1:0:0:0567:5673:23b5'],
            ['2345:425:2CA1:0000:0000:567:5673:23b5'],
            ['2345:0425:2CA1:0000:0000:0567:5673:23b5']
        ];
    }

    /**
     * @dataProvider validIpV4Provider
     * @dataProvider validIpV6Provider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testValidIp($ip) : void
    {
        $valid = $this->validator->validate('ip', $ip)->isInternetAddress()->isIp()->isValid();
        $this->assertTrue($valid, "$ip is not a valid IP address");
    }

    /**
     * @dataProvider validIpV4Provider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testValidIpV4($ip) : void
    {
        $valid = $this->validator->validate('ip', $ip)->isInternetAddress()->isIpv4()->isValid();
        $this->assertTrue($valid, "$ip is not a valid IP address");
    }

    /**
     * @dataProvider validIpV6Provider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testValidIpV6($ip) : void
    {
        $valid = $this->validator->validate('ip', $ip)->isInternetAddress()->isIpv6()->isValid();
        $this->assertTrue($valid, "$ip is not a valid IP address");
    }

    public function invalidIpV4Provider() : array
    {
        return [
            ['276.188.62.239'],
            ['175.153.174.211.algo'],
            ['160.532.235.218.987'],
            ['59.119.844.105'],
            ['1635.55.84.124.33'],
            ['174.125.146.96.876'],
            ['39.2581.238'],
            [3980945],
            ['Ejemplo'],
            [[34,65,'hola', '.....']]
        ];
    }

    public function invalidIpV6Provider() : array
    {
        return [
            ['3e45:3f0:9c82:4663:2a78:9b3c:1c22:5b5e'],
            ['2025:6466:4280:861c:cb81:992c:a51d:3f48::90'],
            ['84ab:68fb:c7:9bbc:b478:2846:351c:3a53kl'],
            ['d31a:a11f:d466:b72a:ab53:ecae:329c:67jdf7'],
            ['0f85:3-*=-698:4fe0:4669:88e3:a96a:5ad7:7f65'],
            ['8ddb:3352:8fdc:4016:059d:6ebc:39c6:4789'],
            ['2345:0425:2CA1:34982:0567:5673:23b5'],
            ['2345:425:2CA1::::0000:567:5673:23b5'],
            ['2345:0425:2CA1:0000:00.00:0567:5673:23b5'],
            [[34,'hola', '???', 34]]
        ];
    }

    /**
     * @dataProvider validIpV4Provider
     * @dataProvider validIpV6Provider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testInvalidIp($ip) : void
    {
        $valid = $this->validator->validate('ip', $ip)->isInternetAddress()->isIp()->isValid();
        $this->assertTrue($valid, json_encode($ip) . " is a valid IP address");
    }

    /**
     * @dataProvider validIpV4Provider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testInvalidIpV4($ip) : void
    {
        $valid = $this->validator->validate('ip', $ip)->isInternetAddress()->isIpv4()->isValid();
        $this->assertTrue($valid, json_encode($ip) . " is a valid IP address");
    }

    /**
     * @dataProvider validIpV6Provider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testInvalidIpV6($ip) : void
    {
        $valid = $this->validator->validate('ip', $ip)->isInternetAddress()->isIpv6()->isValid();
        $this->assertTrue($valid,  json_encode($ip) . " is a valid IP address");
    }

    public function validMacAddressProvider() : array
    {
        return [
            ['ed:94:8a:f7:bd:d0'],
            ['a6:bb:70:57:ed:ff'],
            ['f0:89:a4:4d:29:b6'],
            ['60:37:9e:c6:66:57'],
            ['22:a5:9c:35:8d:28'],
            ['E9:F4:24:40:C3:FD'],
            ['19:CA:2B:CE:BF:A1'],
            ['3D:9C:91:9B:18:DB'],
            ['A0:73:3C:91:FF:30'],
            ['9B:FF:92:A2:D5:C6']
        ];
    }

    public function invalidMacAddressProvider() : array
    {
        return [
            [9845],
            ['a6:70:57:ed:ff'],
            ['f0::89::a4:4d:29:b6'],
            ['60:37:9ke:c6:6s6:57'],
            ['22:a5:9800::35:8d:28'],
            [[34, '4::5', 'hola...']]
        ];
    }

    /**
     * @dataProvider validMacAddressProvider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testValidMacAddress($mac) : void
    {
        $valid = $this->validator->validate('mac', $mac)->isInternetAddress()->isMacAddress()->isValid();
        $this->assertTrue($valid,  "$mac is invalid mac address");
    }

    /**
     * @dataProvider invalidMacAddressProvider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testInvalidMacAddress($mac) : void
    {
        $valid = $this->validator->validate('mac', $mac)->isInternetAddress()->isMacAddress()->isValid();
        $this->assertFalse($valid,  json_encode($mac) . "  is a valid mac address");
    }

    public function validUrlProvider() : array
    {
        return [
            ['https://aribnb.com/?boat=basketball'],
            ['https://www.google.com/'],
            ['https://www.yajoo.com/advertisement.php'],
            ['http://www.netbeans.com/?agreement=authority'],
            ['https://www.msdn.net/achiever/bath?air=bone&apparatus=beds'],
            ['http://cubaneden.com?art=belief&box=act'],
            ['https://www.hide.me'],
            ['http://www.example.org/belief.php'],
            ['https://subdomain.example.com/addition?bag=base'],
            ['http://www.example.subdomain.com/attraction.php'],
            ['http://www.scope.admin.com/index'],
            ['http://mail.outlook.com/mail?folder=inbox']
        ];
    }

    /**
     * @dataProvider validUrlProvider
     * @covers \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
     */
    public function testValidUrl($url) : void
    {
        $valid = $this->validator->validate('url', $url)->isInternetAddress()->isValidUrl()->isValid();
        $this->assertTrue($valid,  "$url is invalid url");
    }
}