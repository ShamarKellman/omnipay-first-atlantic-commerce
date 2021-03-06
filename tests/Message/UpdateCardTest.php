<?php

namespace Omnipay\FirstAtlanticCommerce\Tests\Message;

use Omnipay\FirstAtlanticCommerce\CreditCard;
use Omnipay\FirstAtlanticCommerce\Message\Requests\UpdateCardRequest;
use Omnipay\FirstAtlanticCommerce\Message\Responses\UpdateCardResponse;
use Omnipay\Tests\TestCase;

class UpdateCardTest extends TestCase
{
    /**
     * @var UpdateCardRequest
     */
    private UpdateCardRequest $request;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UpdateCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantId' => 123456,
            'merchantPassword' => 'abcdefg',
            'acquirerId' => 12345,
            'card' => new CreditCard([
                'number' => '411111_000011111',
                'expiryMonth' => random_int(1, 12),
                'expiryYear' => gmdate('Y') + random_int(1, 5),
                'cvv' => '111',
            ]),
            'customerReference' => '1234567',
        ]);
    }

    public function testCreateCardSuccess(): void
    {
        $this->setMockHttpResponse('UpdateCardSuccess.txt');

        /** @var UpdateCardResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('411111_000011111', $response->getToken());
    }

    public function testCreateCardFailure(): void
    {
        $this->setMockHttpResponse('UpdateCardFailure.txt');

        /** @var UpdateCardResponse $response */
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNotSame('411111_000011111', $response->getToken());
    }
}
