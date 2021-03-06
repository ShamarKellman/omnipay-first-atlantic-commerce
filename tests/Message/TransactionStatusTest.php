<?php

namespace Omnipay\FirstAtlanticCommerce\Tests\Message;

use Omnipay\FirstAtlanticCommerce\Message\Requests\TransactionStatusRequest;
use Omnipay\FirstAtlanticCommerce\Message\Responses\TransactionStatusResponse;
use Omnipay\Tests\TestCase;

class TransactionStatusTest extends TestCase
{
    /**
     * @var TransactionStatusRequest
     */
    private TransactionStatusRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new TransactionStatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantId' => 123456,
            'merchantPassword' => 'abcdefg',
            'acquirerId' => 12345,
            'transactionId' => '1234',
        ]);
    }

    public function testTransactionStatusSuccess(): void
    {
        $this->setMockHttpResponse('TransactionStatusSuccess.txt');

        /** @var TransactionStatusResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('1', $response->getCode());
        self::assertSame('1', $response->getResponseCode());
        self::assertSame('Transaction is approved.', $response->getMessage());
    }

    public function testTransactionStatusFailure(): void
    {
        $this->setMockHttpResponse('TransactionStatusFailure.txt');

        /** @var TransactionStatusResponse $response */
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertSame('5', $response->getCode());
        self::assertSame('3', $response->getResponseCode());
        self::assertSame('No Response', $response->getMessage());
    }
}
