<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Responses\HostedPageAuthorizationResponse;
use Omnipay\FirstAtlanticCommerce\Traits\GeneratesSignature;
use Omnipay\FirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class HostedPagePreprocessRequest extends AbstractRequest
{
    use ParameterTrait;
    use GeneratesSignature;

    protected string $requestName = 'HostedPagePreprocess';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @return HostedPageAuthorizationResponse
     * @throws InvalidRequestException
     * @throws InvalidResponseException
     */
    protected function newResponse($xml): HostedPageAuthorizationResponse
    {
        return new HostedPageAuthorizationResponse($this, $xml);
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate(
            'merchantId',
            'merchantPassword',
            'acquirerId',
            'transactionId',
            'amount',
            'currency',
            'cardHolderResponseURL',
            'transactionCode',
            'pageName',
            'pageSet',
        );

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

    public function getPageSet()
    {
        return $this->getParameter('pageSet');
    }

    public function getPageName()
    {
        return $this->getParameter('pageName');
    }

    public function setPageSet(string $value): HostedPagePreprocessRequest
    {
        return $this->setParameter('pageSet', $value);
    }

    public function setPageName(string $value): HostedPagePreprocessRequest
    {
        return $this->setParameter('pageName', $value);
    }
}
