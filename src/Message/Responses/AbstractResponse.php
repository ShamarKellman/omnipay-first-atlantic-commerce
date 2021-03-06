<?php

namespace Omnipay\FirstAtlanticCommerce\Message\Responses;

use \Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use SimpleXMLElement;

class AbstractResponse extends BaseAbstractResponse
{
    protected function xmlDeserialize($xml): array
    {
        $array = [];

        if (! $xml instanceof SimpleXMLElement) {
            $xml = new SimpleXMLElement($xml);
        }

        foreach ($xml->children() as $key => $child) {
            $value = (string) $child;
            $_children = $this->xmlDeserialize($child);
            $_push = ($_hasChild = (count($_children) > 0)) ? $_children : $value;

            if ($_hasChild && ! empty($value) && $value !== '') {
                $_push[] = $value;
            }

            $array[$key] = $_push;
        }

        return $array;
    }

    public function getTransactionId(): ?string
    {
        return $this->data['OrderNumber'] ?? null;
    }

    public function isSuccessful()
    {
        // TODO: Implement isSuccessful() method.
    }
}
