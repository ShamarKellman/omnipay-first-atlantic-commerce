<?php

namespace Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests;

use Omnipay\OmnipayFirstAtlanticCommerce\Message\Responses\TransactionStatusResponse;
use Omnipay\OmnipayFirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class TransactionStatusRequest extends AbstractRequest
{
    use ParameterTrait;

    protected string $requestName = 'TransactionStatusRequest';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @return TransactionStatusResponse
     */
    protected function newResponse($xml): TransactionStatusResponse
    {
        return new TransactionStatusResponse($this, $xml);
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('merchantId', 'merchantPassword', 'acquirerId', 'transactionId');

        return [
            'MerchantId' => $this->getMerchantId(),
            'AcquirerId' => $this->getAcquirerId(),
            'Password' => $this->getMerchantPassword(),
            'OrderNumber' => $this->getTransactionId(),
        ];
    }
}
