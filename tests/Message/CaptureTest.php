<?php

namespace Omnipay\OmnipayFirstAtlanticCommerce\Tests\Message;

use Omnipay\OmnipayFirstAtlanticCommerce\Enums\ModificationType;
use Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests\TransactionModificationRequest;
use Omnipay\OmnipayFirstAtlanticCommerce\Message\Responses\TransactionModificationResponse;
use Omnipay\Tests\TestCase;

class CaptureTest extends TestCase
{
    /**
     * @var TransactionModificationRequest
     */
    private TransactionModificationRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new TransactionModificationRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantId' => 123456,
            'merchantPassword' => 'abcdefg',
            'acquirerId' => 12345,
            'testMode' => true,
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionId' => '1234',
            'modificationType' => ModificationType::CAPTURE,
        ]);
    }

    public function testCaptureSuccess(): void
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');

        /** @var TransactionModificationResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('1101', $response->getCode());
        self::assertSame('1', $response->getResponseCode());
        self::assertSame('Success', $response->getMessage());
        self::assertSame('00', $response->getOriginalResponseCode());
    }

    public function testCaptureFailure(): void
    {
        $this->setMockHttpResponse('CaptureFailure.txt');

        /** @var TransactionModificationResponse $response */
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertSame('1100', $response->getCode());
        self::assertSame('3', $response->getResponseCode());
        self::assertSame('Failed', $response->getMessage());
        self::assertSame('1', $response->getOriginalResponseCode());
    }
}
