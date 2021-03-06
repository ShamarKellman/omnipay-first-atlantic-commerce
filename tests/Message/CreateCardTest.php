<?php

namespace Omnipay\FirstAtlanticCommerce\Tests\Message;

use Omnipay\FirstAtlanticCommerce\Message\Requests\CreateCardRequest;
use Omnipay\FirstAtlanticCommerce\Message\Responses\CreateCardResponse;
use Omnipay\Tests\TestCase;

class CreateCardTest extends TestCase
{
    /**
     * @var CreateCardRequest
     */
    private CreateCardRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantId' => 123456,
            'merchantPassword' => 'abcdefg',
            'acquirerId' => 12345,
            'card' => $this->getValidCard(),
            'customerReference' => '1234567',
        ]);
    }

    public function testCreateCardSuccess(): void
    {
        $this->setMockHttpResponse('CreateCardSuccess.txt');

        /** @var CreateCardResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('411111_000011111', $response->getToken());
    }

    public function testCreateCardFailure(): void
    {
        $this->setMockHttpResponse('CreateCardFailure.txt');

        /** @var CreateCardResponse $response */
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNotSame('411111_000011111', $response->getToken());
    }
}
