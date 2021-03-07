<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Requests;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use SimpleXMLElement;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected string $liveEndpoint = 'https://marlin.firstatlanticcommerce.com/PGServiceXML/';

    protected string $testEndpoint = 'https://ecm.firstatlanticcommerce.com/PGServiceXML/';

    protected string $namespace = 'http://schemas.firstatlanticcommerce.com/gateway/data';

    protected string $requestName;

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * Returns the amount formatted to match FAC's expectations.
     *
     * @return string The amount padded with zeros on the left to 12 digits and no decimal place.
     * @throws InvalidRequestException
     */
    public function formatAmount(): string
    {
        $amount = str_replace('.', '', $this->getAmount());

        return str_pad($amount, 12, '0', STR_PAD_LEFT);
    }

    /**
     * @param $data
     * @return \Omnipay\Common\Message\ResponseInterface
     * @throws \Exception
     */
    public function sendData($data): ResponseInterface
    {
        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->requestEndpoint(),
            ['Content-Type' => 'text/xml; charset=utf-8'],
            $this->xmlSerialize($data)
        );

        return $this->createResponse($httpResponse->getBody()->getContents(), $httpResponse->getHeaders());
    }

    abstract protected function newResponse(SimpleXMLElement $xml);

    protected function createResponse($data, $headers = [])
    {
        return $this->response = $this->newResponse($data);
    }

    public function setCard($value): AbstractRequest
    {
        if ($value && ! $value instanceof CreditCard) {
            $value = new CreditCard($value);
        }

        return $this->setParameter('card', $value);
    }

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getCurrencyNumeric(): ?string
    {
        return str_pad(parent::getCurrencyNumeric(), 3, 0, STR_PAD_LEFT);
    }

    /**
     * @param  array  $data
     * @param  null  $xml
     * @return string
     * @throws \Exception
     */
    protected function xmlSerialize(array $data, $xml = null): string
    {
        if (! $xml instanceof SimpleXMLElement) {
            $xml = new SimpleXMLElement('<'. $this->requestDataType() .' xmlns="'. $this->namespace .'" />');
        }

        foreach ($data as $key => $value) {
            if (! isset($value)) {
                continue;
            }

            if (is_array($value)) {
                $node = $xml->addChild($key);
                $this->xmlSerialize($value, $node);
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }

    public function requestDataType(): string
    {
        return $this->requestName . 'Request';
    }

    public function requestEndpoint(): string
    {
        return $this->getEndpoint() . $this->requestName;
    }
}
