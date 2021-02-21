<?php

namespace Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\OmnipayFirstAtlanticCommerce\Message\Responses\CreateCardResponse;
use Omnipay\OmnipayFirstAtlanticCommerce\Traits\GeneratesSignature;
use Omnipay\OmnipayFirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class CreateCardRequest extends AbstractRequest
{
    use ParameterTrait;
    use GeneratesSignature;

    protected string $requestName = 'TokenizeRequest';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @return CreateCardResponse
     * @throws InvalidResponseException
     */
    protected function newResponse($xml): CreateCardResponse
    {
        return new CreateCardResponse($this, $xml);
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('merchantId', 'merchantPassword', 'acquirerId', 'card', 'customerReference');

        $this->getCard()->validate('number');

        return [
            'CardNumber' => $this->getCard()->getNumber(),
            'CustomerReference' => $this->getCustomerReference(),
            'ExpiryDate' => $this->getCard()->getExpiryDate('my'),
            'MerchantNumber' => $this->getMerchantId(),
            'Signature' => $this->generateSignature(),
        ];
    }

    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    public function setCustomerReference($value): CreateCardRequest
    {
        return $this->setParameter('customerReference', $value);
    }
}
