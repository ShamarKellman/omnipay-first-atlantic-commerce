<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Requests;

use Omnipay\FirstAtlanticCommerce\Enums\TransactionCode;
use Omnipay\FirstAtlanticCommerce\Message\Responses\PurchaseResponse;
use Omnipay\FirstAtlanticCommerce\Traits\GeneratesSignature;
use Omnipay\FirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class PurchaseRequest extends AbstractRequest
{
    use ParameterTrait;
    use GeneratesSignature;

    protected string $requestName = 'Authorize';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @return PurchaseResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    protected function newResponse($xml): PurchaseResponse
    {
        return new PurchaseResponse($this, $xml);
    }

    /**
     * @return array
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
        return $this->getCreateCard() ?
            (TransactionCode::REQUEST_TOKEN + TransactionCode::SINGLE_PASS) :
            TransactionCode::SINGLE_PASS;
    }

    public function setCreateCard($value): PurchaseRequest
    {
        return $this->setParameter('createCard', $value);
    }

    public function getCreateCard()
    {
        return $this->getParameter('createCard');
    }
}
