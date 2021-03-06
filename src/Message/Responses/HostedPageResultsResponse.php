<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Responses;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Requests\HostedPageResultsRequest;

class HostedPageResultsResponse extends AbstractResponse
{
    /**
     * @param  HostedPageResultsRequest  $request
     * @param  string  $data
     * @throws InvalidResponseException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function __construct(HostedPageResultsRequest $request, string $data)
    {
        parent::__construct($request, $data);

        if (empty($data)) {
            throw new InvalidResponseException();
        }

        $this->request = $request;
        $this->data = $this->xmlDeserialize($data);
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['AuthResponse']['CreditCardTransactionResults']['ResponseCode']) &&
        $this->data['AuthResponse']['CreditCardTransactionResults']['ResponseCode'] === '1';
    }

    public function getCode(): ?string
    {
        return $this->data['AuthResponse']['CreditCardTransactionResults']['ReasonCode'] ?? null;
    }

    public function getResponseCode(): ?string
    {
        return $this->data['AuthResponse']['CreditCardTransactionResults']['ResponseCode'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['AuthResponse']['CreditCardTransactionResults']['ReasonCodeDescription'] ?? null;
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['AuthResponse']['CreditCardTransactionResults']['ReferenceNumber'] ?? null;
    }

    public function getTransactionId(): ?string
    {
        return $this->data['AuthResponse']['OrderNumber'] ?? null;
    }

    public function getCardReference(): ?string
    {
        return $this->data['AuthResponse']['CreditCardTransactionResults']['TokenizedPAN'] ?? null;
    }
}
