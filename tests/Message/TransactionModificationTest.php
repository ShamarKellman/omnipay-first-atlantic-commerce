<?php

namespace Omnipay\FirstAtlanticCommerce\Tests\Message;

use Omnipay\FirstAtlanticCommerce\Enums\ModificationType;
use Omnipay\FirstAtlanticCommerce\Message\Requests\TransactionModificationRequest;
use Omnipay\FirstAtlanticCommerce\Message\Responses\TransactionModificationResponse;
use Omnipay\Tests\TestCase;

class TransactionModificationTest extends TestCase
{
    /**
     * @var TransactionModificationRequest
     */
    private TransactionModificationRequest $request;

    private array $options = [
        'merchantId' => 123456,
        'merchantPassword' => 'abcdefg',
        'acquirerId' => 12345,
        'testMode' => true,
        'amount' => '10.00',
        'currency' => 'USD',
        'transactionId' => '1234',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new TransactionModificationRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testCaptureSuccess(): void
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');
        $this->options['modificationType'] = ModificationType::CAPTURE;
        $this->request->initialize($this->options);

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
        $this->options['modificationType'] = ModificationType::CAPTURE;
        $this->request->initialize($this->options);

        /** @var TransactionModificationResponse $response */
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertSame('1100', $response->getCode());
        self::assertSame('3', $response->getResponseCode());
        self::assertSame('Failed', $response->getMessage());
        self::assertSame('1', $response->getOriginalResponseCode());
    }

    public function testRefundSuccess(): void
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');
        $this->options['modificationType'] = ModificationType::REFUND;
        $this->request->initialize($this->options);

        /** @var TransactionModificationResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('1101', $response->getCode());
        self::assertSame('1', $response->getResponseCode());
        self::assertSame('Success', $response->getMessage());
        self::assertSame('00', $response->getOriginalResponseCode());
    }

    public function testReversalSuccess(): void
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');
        $this->options['modificationType'] = ModificationType::REVERSAL;
        $this->request->initialize($this->options);

        /** @var TransactionModificationResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('1101', $response->getCode());
        self::assertSame('1', $response->getResponseCode());
        self::assertSame('Success', $response->getMessage());
        self::assertSame('00', $response->getOriginalResponseCode());
    }
}
