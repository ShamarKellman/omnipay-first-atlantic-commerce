<?php

namespace Omnipay\FirstAtlanticCommerce\Tests\Message;

use Omnipay\FirstAtlanticCommerce\Message\Requests\AuthorizeRequest;
use Omnipay\FirstAtlanticCommerce\Message\Responses\AuthorizeResponse;
use Omnipay\Tests\TestCase;

class AuthorizeTest extends TestCase
{
    /**
     * @var AuthorizeRequest
     */
    private AuthorizeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantId' => 123456,
            'merchantPassword' => 'abcdefg',
            'acquirerId' => 12345,
            'testMode' => true,
            'amount' => '10.00',
            'currency' => 'USD',
            'card' => $this->getValidCard(),
            'transactionId' => '1234',
        ]);
    }

    public function testAuthorizeSuccess(): void
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('1', $response->getCode());
        self::assertSame('1', $response->getResponseCode());
    }

    public function testAuthorizeDecline(): void
    {
        $this->setMockHttpResponse('AuthorizeDecline.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertSame('2', $response->getCode());
        self::assertSame('2', $response->getResponseCode());
    }

    public function testAuthorizeFailure(): void
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertSame('5', $response->getCode());
        self::assertSame('3', $response->getResponseCode());
    }
}
