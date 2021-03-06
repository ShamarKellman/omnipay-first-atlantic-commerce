<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Responses;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Requests\TransactionModificationRequest;

class TransactionModificationResponse extends AbstractResponse
{
    /**
     * @param  TransactionModificationRequest  $request
     * @param  string  $data
     * @throws InvalidResponseException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function __construct(TransactionModificationRequest $request, string $data)
    {
        parent::__construct($request, $data);

        if (empty($data)) {
            throw new InvalidResponseException();
        }

        $this->request = $request;
        $this->data = $this->xmlDeserialize($data);
    }

    public function isSuccessful(): bool
    {
        return isset($this->data['ResponseCode']) && $this->data['ResponseCode'] === '1';
    }

    public function getCode(): ?string
    {
        return $this->data['ReasonCode'] ?? null;
    }

    public function getResponseCode(): ?string
    {
        return $this->data['ResponseCode'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['ReasonCodeDescription'] ?? null;
    }

    public function getOriginalResponseCode(): ?string
    {
        return $this->data['OriginalResponseCode'] ?? null;
    }
}
