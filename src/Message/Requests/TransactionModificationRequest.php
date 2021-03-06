<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Responses\TransactionModificationResponse;
use Omnipay\FirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class TransactionModificationRequest extends AbstractRequest
{
    use ParameterTrait;

    protected string $requestName = 'TransactionModification';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @throws InvalidRequestException
     * @throws InvalidResponseException
     * @return TransactionModificationResponse
     */
    protected function newResponse($xml): TransactionModificationResponse
    {
        return new TransactionModificationResponse($this, $xml);
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('modificationType', 'merchantId', 'merchantPassword', 'acquirerId', 'transactionId', 'amount', 'currency');

        return [
            'ModificationType' => $this->getModificationType(),
            'MerchantId' => $this->getMerchantId(),
            'Password' => $this->getMerchantPassword(),
            'AcquirerId' => $this->getAcquirerId(),
            'OrderNumber' => $this->getTransactionId(),
            'Amount' => $this->formatAmount(),
            'Currency' => $this->getCurrencyNumeric(),
            'CurrencyExponent' => $this->getCurrencyDecimalPlaces(),
       ];
    }

    public function getModificationType()
    {
        return $this->getParameter('modificationType');
    }

    public function setModificationType($value): TransactionModificationRequest
    {
        return $this->setParameter('modificationType', $value);
    }
}
