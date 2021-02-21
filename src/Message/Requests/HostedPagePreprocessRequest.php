<?php

namespace Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests;

use Omnipay\OmnipayFirstAtlanticCommerce\Message\Responses\HostedPageAuthorizationResponse;
use Omnipay\OmnipayFirstAtlanticCommerce\Traits\GeneratesSignature;
use Omnipay\OmnipayFirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class HostedPagePreprocessRequest extends AbstractRequest
{
    use ParameterTrait;
    use GeneratesSignature;

    protected string $requestName = 'HostedPagePreprocessRequest';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @return HostedPageAuthorizationResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    protected function newResponse($xml): HostedPageAuthorizationResponse
    {
        return new HostedPageAuthorizationResponse($this, $xml);
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('merchantId', 'merchantPassword', 'acquirerId', 'transactionId', 'amount', 'currency', 'cardHolderResponseURL', 'transactionCode');

        $transactionDetails = [
            'AcquirerId' => $this->getAcquirerId(),
            'Amount' => $this->formatAmount(),
            'Currency' => $this->getCurrencyNumeric(),
            'CurrencyExponent' => $this->getCurrencyDecimalPlaces(),
            'IPAddress' => $this->getClientIp(),
            'MerchantId' => $this->getMerchantId(),
            'OrderNumber' => $this->getTransactionId(),
            'Signature' => $this->generateSignature(),
            'SignatureMethod' => 'SHA1',
            'TransactionCode' => $this->getTransactionCode(),
        ];

        return [
            'CardHolderResponseURL' => $this->getCardHolderResponseURL(),
            'TransactionDetails' => $transactionDetails,
        ];
    }

    public function getCardHolderResponseURL()
    {
        return $this->getParameter('cardHolderResponseURL');
    }

    public function setCardHolderResponseURL(string $value): HostedPagePreprocessRequest
    {
        return $this->setParameter('cardHolderResponseURL', $value);
    }

    public function getTransactionCode()
    {
        return $this->getParameter('transactionCode');
    }

    public function setTransactionCode(string $value): HostedPagePreprocessRequest
    {
        return $this->setParameter('transactionCode', $value);
    }
}