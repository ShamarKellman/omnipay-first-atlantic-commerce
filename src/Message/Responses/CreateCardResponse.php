<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Responses;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Requests\CreateCardRequest;

class CreateCardResponse extends AbstractResponse
{
    /**
     * @param  CreateCardRequest  $request
     * @param  string  $data
     * @throws InvalidResponseException
     */
    public function __construct(CreateCardRequest $request, string $data)
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
        return isset($this->data['Success']) && $this->data['Success'] === 'true';
    }

    public function getMessage(): ?string
    {
        return $this->data['ErrorMsg'] ?? null;
    }

    public function getToken(): ?string
    {
        return $this->data['Token'] ?? null;
    }
}
