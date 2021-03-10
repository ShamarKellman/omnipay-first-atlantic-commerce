<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Responses\TransactionStatusResponse;
use Omnipay\FirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class TransactionStatusRequest extends AbstractRequest
{
    use ParameterTrait;

    protected string $requestName = 'TransactionStatus';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @throws InvalidResponseException
     * @return TransactionStatusResponse
     */
    protected function newResponse($xml): TransactionStatusResponse
    {
        return new TransactionStatusResponse($this, $xml);
    }

    /**
     * @return array
     * @throws InvalidRequestException
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
