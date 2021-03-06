<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Requests;

use Omnipay\FirstAtlanticCommerce\Enums\TransactionCode;
use Omnipay\FirstAtlanticCommerce\Message\Responses\AuthorizeResponse;
use Omnipay\FirstAtlanticCommerce\Traits\GeneratesSignature;
use Omnipay\FirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class AuthorizeRequest extends AbstractRequest
{
    use ParameterTrait;
    use GeneratesSignature;

    protected string $requestName = 'Authorize';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @return AuthorizeResponse
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function newResponse($xml): AuthorizeResponse
    {
        return new AuthorizeResponse($this, $xml);
    }

    /**
     * @inheritDoc
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function getData(): array
    {
        $this->validate('merchantId', 'merchantPassword', 'acquirerId', 'transactionId', 'amount', 'currency', 'card');

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

        $this->getCard()->validate();

        $cardDetails = [
            'CardCVV2' => $this->getCard()->getCvv(),
            'CardExpiryDate' => $this->getCard()->getExpiryDate('my'),
            'CardNumber' => $this->getCard()->getNumber(),
            'IssueNumber' => $this->getCard()->getIssueNumber(),
        ];

        return [
            'TransactionDetails' => $transactionDetails,
            'CardDetails' => $cardDetails,
        ];
    }

    public function getTransactionCode(): int
    {
        return $this->getCreateCard() ? TransactionCode::REQUEST_TOKEN : TransactionCode::NONE;
    }

    public function setCreateCard($value): AuthorizeRequest
    {
        return $this->setParameter('createCard', $value);
    }

    public function getCreateCard()
    {
        return $this->getParameter('createCard');
    }
}
