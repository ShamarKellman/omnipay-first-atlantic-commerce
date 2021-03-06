<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Responses;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Requests\PurchaseRequest;

class PurchaseResponse extends AbstractResponse
{
    /**
     * @param  PurchaseRequest  $request
     * @param  string  $data
     * @throws InvalidResponseException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function __construct(PurchaseRequest $request, string $data)
    {
        parent::__construct($request, $data);

        if (empty($data)) {
            throw new InvalidResponseException();
        }

        $this->request = $request;
        $this->data = $this->xmlDeserialize($data);

        $this->verifySignature();
    }

    /**
     * Verifies the signature for the response.
     *
     * @throws InvalidResponseException if the signature doesn't match
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     *
     * @return void
     */
    public function verifySignature(): void
    {
        if (isset($this->data['CreditCardTransactionResults']['ResponseCode']) && (
            '1' === $this->data['CreditCardTransactionResults']['ResponseCode'] ||
                '2' === $this->data['CreditCardTransactionResults']['ResponseCode']
        )) {
            $signature = $this->request->getMerchantPassword();
            $signature .= $this->request->getMerchantId();
            $signature .= $this->request->getAcquirerId();
            $signature .= $this->request->getTransactionId();
            $signature .= $this->request->formatAmount();
            $signature .= $this->request->getCurrencyNumeric();

            $signature = base64_encode(sha1($signature, true));

            if ($signature !== $this->data['Signature']) {
                throw new InvalidResponseException('Signature verification failed');
            }
        }
    }

    public function isSuccessful(): bool
    {
        return isset($this->data['CreditCardTransactionResults']['ResponseCode']) && $this->data['CreditCardTransactionResults']['ResponseCode'] === '1';
    }

    public function getCode(): ?string
    {
        return $this->data['CreditCardTransactionResults']['ReasonCode'] ?? null;
    }

    public function getResponseCode(): ?string
    {
        return $this->data['CreditCardTransactionResults']['ResponseCode'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['CreditCardTransactionResults']['ReasonCodeDescription'] ?? null;
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['CreditCardTransactionResults']['ReferenceNumber'] ?? null;
    }

    public function getCardReference(): ?string
    {
        return $this->data['CreditCardTransactionResults']['TokenizedPAN'] ?? null;
    }
}
