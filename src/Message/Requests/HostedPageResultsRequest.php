<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstAtlanticCommerce\Message\Responses\HostedPageResultsResponse;
use SimpleXMLElement;

class HostedPageResultsRequest extends AbstractRequest
{
    protected string $requestName = 'HostedPageResults';

    /**
     * @param  SimpleXMLElement|string  $xml
     * @throws InvalidRequestException
     * @throws InvalidResponseException
     * @return HostedPageResultsResponse
     */
    protected function newResponse($xml): HostedPageResultsResponse
    {
        return new HostedPageResultsResponse($this, $xml);
    }

    /**
     * @return mixed|void
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('token');

        return [
            'string' => $this->getToken(),
        ];
    }

    protected function xmlSerialize(array $data, $xml = null): string
    {
        $string = '<?xml version="1.0"?>';
        $string .= '<string xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://schemas.firstatlanticcommerce.com/gateway/data">';
        $string .= $data['string'];
        $string .= '</string>';

        return $string;
    }
}
