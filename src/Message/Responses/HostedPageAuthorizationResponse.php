<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Responses;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Requests\HostedPagePreprocessRequest;

class HostedPageAuthorizationResponse extends AbstractResponse
{
    /**
     * @param  HostedPagePreprocessRequest  $request
     * @param  string  $data
     * @throws InvalidResponseException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function __construct(HostedPagePreprocessRequest $request, string $data)
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
        return isset($this->data['ResponseCode']) && $this->data['ResponseCode'] === '0';
    }

    public function getCode(): ?string
    {
        return $this->data['ResponseCode'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['ResponseCodeDescription'] ?? null;
    }

    public function getToken(): ?string
    {
        return $this->data['SecurityToken'] ?? null;
    }

    public function getRedirectUrl(): string
    {
        $url = preg_replace('/PGServiceXML\//', '', $this->request->getEndpoint());

        return "{$url}MerchantPages/{$this->request->getPageSet()}/{$this->request->getPageName()}/{$this->getToken()}";
    }
}
