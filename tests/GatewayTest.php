<?php

namespace Omnipay\FirstAtlanticCommerce\Tests;

use Omnipay\FirstAtlanticCommerce\CreditCard;
use Omnipay\FirstAtlanticCommerce\Enums\ModificationType;
use Omnipay\FirstAtlanticCommerce\Enums\TransactionCode;
use Omnipay\FirstAtlanticCommerce\Gateway;
use Omnipay\FirstAtlanticCommerce\Message\Requests\AuthorizeRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\CreateCardRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\HostedPagePreprocessRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\HostedPageResultsRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\PurchaseRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\TransactionModificationRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\TransactionStatusRequest;
use Omnipay\Omnipay;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId(123456)
            ->setMerchantPassword('abcdefg')
            ->setAcquirerId(12345)
            ->setTestMode(true);
    }

    public function testGatewayInstantiated()
    {
        $gateway = Omnipay::create('FirstAtlanticCommerce');

        $this->assertInstanceOf(Gateway::class, $gateway);
    }

    public function testGatewayCanBeInitialized(): void
    {
        self::assertEquals('First Atlantic Commerce.', $this->gateway->getName());
        self::assertEquals('FAC', $this->gateway->getShortName());
        self::assertArraySubset([
            'merchantId' => 123456,
            'merchantPassword' => 'abcdefg',
            'acquirerId' => 12345,
            'testMode' => true,
        ], $this->gateway->getParameters());
    }

    public function testAuthorize(): void
    {
        $request = $this->gateway->authorize([
        'amount' => '10.00',
        'currency' => 'USD',
        'transactionId' => '1234',
        'card' => $this->getValidCard(),
    ]);

        self::assertInstanceOf(AuthorizeRequest::class, $request);
        self::assertSame('000000001000', $request->getData()['TransactionDetails']['Amount']);
        self::assertSame('840', $request->getData()['TransactionDetails']['Currency']);
    }

    public function testPurchase(): void
    {
        $request = $this->gateway->purchase([
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionId' => '1234',
            'card' => $this->getValidCard(),
        ]);

        self::assertInstanceOf(PurchaseRequest::class, $request);
        self::assertSame('000000001000', $request->getData()['TransactionDetails']['Amount']);
        self::assertSame('840', $request->getData()['TransactionDetails']['Currency']);
        self::assertSame(8, $request->getData()['TransactionDetails']['TransactionCode']);
    }

    public function testPurchaseWithCreateCard(): void
    {
        $request = $this->gateway->purchase([
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionId' => '1234',
            'card' => $this->getValidCard(),
            'createCard' => true,
        ]);

        self::assertInstanceOf(PurchaseRequest::class, $request);
        self::assertSame('000000001000', $request->getData()['TransactionDetails']['Amount']);
        self::assertSame('840', $request->getData()['TransactionDetails']['Currency']);
        self::assertSame(136, $request->getData()['TransactionDetails']['TransactionCode']);
    }

    public function testCapture(): void
    {
        $request = $this->gateway->capture([
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionId' => '1234',
        ]);

        self::assertInstanceOf(TransactionModificationRequest::class, $request);
        self::assertSame('000000001000', $request->getData()['Amount']);
        self::assertSame('840', $request->getData()['Currency']);
        self::assertSame(ModificationType::CAPTURE, $request->getData()['ModificationType']);
    }

    public function testRefund(): void
    {
        $request = $this->gateway->refund([
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionId' => '1234',
        ]);

        self::assertInstanceOf(TransactionModificationRequest::class, $request);
        self::assertSame('000000001000', $request->getData()['Amount']);
        self::assertSame('840', $request->getData()['Currency']);
        self::assertSame(ModificationType::REFUND, $request->getData()['ModificationType']);
    }

    public function testReversal(): void
    {
        $request = $this->gateway->void([
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionId' => '1234',
        ]);

        self::assertInstanceOf(TransactionModificationRequest::class, $request);
        self::assertSame('000000001000', $request->getData()['Amount']);
        self::assertSame('840', $request->getData()['Currency']);
        self::assertSame(ModificationType::REVERSAL, $request->getData()['ModificationType']);
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testHostedPage(): void
    {
        /** @var HostedPagePreprocessRequest $request */
        $request = $this->gateway->hostedPage([
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionId' => '1234',
            'transactionCode' => TransactionCode::SINGLE_PASS + TransactionCode::REQUEST_TOKEN,
            'cardHolderResponseURL' => 'https://merchant/response/page.php',
            'pageSet' => 'pageSet',
            'pageName' => 'pageName',
        ]);

        self::assertInstanceOf(HostedPagePreprocessRequest::class, $request);
        self::assertSame('000000001000', $request->getData()['TransactionDetails']['Amount']);
        self::assertSame('840', $request->getData()['TransactionDetails']['Currency']);
        self::assertSame('https://merchant/response/page.php', $request->getData()['CardHolderResponseURL']);
        self::assertSame('136', $request->getData()['TransactionDetails']['TransactionCode']);
        self::assertSame('pageSet', $request->getPageSet());
        self::assertSame('pageName', $request->getPageName());
    }

    public function testHostedPageResult(): void
    {
        $request = $this->gateway->hostedPageResult([
            'token' => '_JBfLQJNiEmFBtnF3AfoeQ2',
        ]);

        self::assertInstanceOf(HostedPageResultsRequest::class, $request);
        self::assertSame('_JBfLQJNiEmFBtnF3AfoeQ2', $request->getData()['string']);
    }

    public function testFetchTransaction(): void
    {
        $request = $this->gateway->fetchTransaction([
            'transactionId' => '1234',
        ]);

        self::assertInstanceOf(TransactionStatusRequest::class, $request);
        self::assertSame('1234', $request->getData()['OrderNumber']);
    }

    public function testCreateCard(): void
    {
        $request = $this->gateway->createCard([
            'card' => $this->getValidCard(),
            'customerReference' => '1234567',
        ]);

        self::assertInstanceOf(CreateCardRequest::class, $request);
        self::assertSame('1234567', $request->getData()['CustomerReference']);
    }

    /**
     * @throws \Exception
     */
    public function testUpdateCard(): void
    {
        $request = $this->gateway->createCard([
            'card' => new CreditCard([
                'number' => '411111_000011111',
                'expiryMonth' => random_int(1, 12),
                'expiryYear' => gmdate('Y') + random_int(1, 5),
            ]),
            'customerReference' => '1234567',
            'cardReference' => '411111_000011111',
        ]);

        self::assertInstanceOf(CreateCardRequest::class, $request);
        self::assertSame('1234567', $request->getData()['CustomerReference']);
    }
}
