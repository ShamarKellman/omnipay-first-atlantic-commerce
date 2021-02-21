<?php

namespace Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests;

use Omnipay\OmnipayFirstAtlanticCommerce\Message\Responses\TransactionModificationResponse;
use Omnipay\OmnipayFirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class TransactionModificationRequest extends AbstractRequest
{
    use ParameterTrait;

    protected string $requestName = 'TransactionModificationRequest';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @return TransactionModificationResponse
     */
    protected function newResponse($xml): TransactionModificationResponse
    {
        return new TransactionModificationResponse($this, $xml);
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
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
