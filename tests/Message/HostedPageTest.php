<?php

namespace Omnipay\FirstAtlanticCommerce\Tests\Message;

use Omnipay\FirstAtlanticCommerce\Enums\TransactionCode;
use Omnipay\FirstAtlanticCommerce\Message\Requests\HostedPagePreprocessRequest;
use Omnipay\FirstAtlanticCommerce\Message\Responses\HostedPageAuthorizationResponse;
use Omnipay\Tests\TestCase;

class HostedPageTest extends TestCase
{
    /**
     * @var HostedPagePreprocessRequest
     */
    private HostedPagePreprocessRequest $request;

    protected function setUp()
    {
        $this->request = new HostedPagePreprocessRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantId' => 123456,
            'merchantPassword' => 'abcdefg',
            'acquirerId' => 12345,
            'testMode' => true,
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionCode' => TransactionCode::SINGLE_PASS + TransactionCode::REQUEST_TOKEN,
            'cardHolderResponseURL' => 'https://merchant/response/page.php',
            'transactionId' => '1234',
            'pageSet' => 'pageSet',
            'pageName' => 'pageName',
        ]);
    }

    public function testGetSingleUseTokenSuccess(): void
    {
        $this->setMockHttpResponse('SingleUseTokenSuccess.txt');

        /** @var HostedPageAuthorizationResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('0', $response->getCode());
        self::assertSame('AzXrogQb5E2aEJDWsyEaw2', $response->getToken());
        self::assertSame('Success', $response->getMessage());
        self::assertSame(
            'https://ecm.firstatlanticcommerce.com/MerchantPages/pageSet/pageName/AzXrogQb5E2aEJDWsyEaw2',
            $response->getRedirectUrl()
        );
    }
}
