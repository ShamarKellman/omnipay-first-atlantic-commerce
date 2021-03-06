<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Requests;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Responses\UpdateCardResponse;
use Omnipay\FirstAtlanticCommerce\Traits\GeneratesSignature;
use Omnipay\FirstAtlanticCommerce\Traits\ParameterTrait;
use SimpleXMLElement;

class UpdateCardRequest extends AbstractRequest
{
    use ParameterTrait;
    use GeneratesSignature;

    protected string $requestName = 'UpdateToken';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @return UpdateCardResponse
     * @throws InvalidResponseException
     */
    protected function newResponse($xml): UpdateCardResponse
    {
        return new UpdateCardResponse($this, $xml);
    }

    /**
     * @return array
     * @throws InvalidRequestException|
     * @throws InvalidCreditCardException
     */
    public function getData(): array
    {
        $this->validate('merchantId', 'merchantPassword', 'acquirerId', 'card', 'customerReference');

        $this->getCard()->validate();

        return [
            'TokenPAN' => $this->getCard()->getNumber(),
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

    public function setCustomerReference($value): UpdateCardRequest
    {
        return $this->setParameter('customerReference', $value);
    }
}
