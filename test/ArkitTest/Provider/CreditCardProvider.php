<?php

namespace ArkitTest\Provider;

class CreditCardProvider implements \Iterator
{
    CONST BASIC_INFORMATION = 'Basic';

    CONST CVC_INFORMATION = 'CVC';

    CONST EXPIRY_INFORMATION = 'Expiry';

    CONST ALL_INFORMATION = 'All';

    private array $info;

    private int $key;

    public function __construct(string $typeInfo)
    {
        // Source information
        $source = dirname(__DIR__) . '/files/credit_cards.json';

        // Read content
        $content = file_get_contents($source);

        // Parse
        $json = json_decode($content, true);

        // Create the info array
        $this->info = [];

        // Fill array
        foreach ($json as $item)
        {
            switch($typeInfo)
            {
                case self::BASIC_INFORMATION:
                    $this->info[] = [
                        'type' => $item['data']['card']['type'],
                        'number' => $item['data']['card']['number']
                    ];
                    break;

                case self::CVC_INFORMATION:
                    $this->info[] = [
                        'type' => $item['data']['card']['type'],
                        'number' => $item['data']['card']['number'],
                        'cvc' => $item['data']['card']['cvv']
                    ];
                    break;

                case self::EXPIRY_INFORMATION:
                    $this->info[] = [
                        'type' => $item['data']['card']['type'],
                        'number' => $item['data']['card']['number'],
                        'expMonth' => $item['data']['card']['expiration-month'],
                        'expYear' => $item['data']['card']['expiration-year']
                    ];
                    break;

                case self::ALL_INFORMATION:
                    $this->info[] = $item['data']['card'];
                    break;
            }

        }

        $this->key = 0;
    }

    /**
     * @inheritDoc
     */
    public function current(): mixed
    {
        return $this->info[$this->key];
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        ++$this->key;
    }

    /**
     * @inheritDoc
     */
    public function key(): mixed
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return isset($this->info[$this->key]);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->key = 0;
    }
}