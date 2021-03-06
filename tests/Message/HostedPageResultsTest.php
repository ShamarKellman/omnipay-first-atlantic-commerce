<?php

namespace Omnipay\FirstAtlanticCommerce\Tests\Message;

use Omnipay\FirstAtlanticCommerce\Message\Requests\HostedPageResultsRequest;
use Omnipay\FirstAtlanticCommerce\Message\Responses\HostedPageResultsResponse;
use Omnipay\Tests\TestCase;

class HostedPageResultsTest extends TestCase
{
    /**
     * @var HostedPageResultsRequest
     */
    private HostedPageResultsRequest $request;

    protected function setUp(): void
    {
        $this->request = new HostedPageResultsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'token' => '_JBfLQJNiEmFBtnF3AfoeQ2',
        ]);
    }

    public function testHostedPageResults(): void
    {
        $this->setMockHttpResponse('HostedPageResultsResponseSuccess.txt');

        /** @var HostedPageResultsResponse $response */
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('1', $response->getCode());
        self::assertSame('1', $response->getResponseCode());
        self::assertSame('Transaction is approved.', $response->getMessage());
        self::assertSame('1234', $response->getTransactionId());
        self::assertSame('307916543749', $response->getTransactionReference());
        self::assertSame('411111_000011111', $response->getCardReference());
    }
}
