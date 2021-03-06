<?php


namespace Omnipay\FirstAtlanticCommerce\Traits;

trait GeneratesSignature
{
    protected function generateSignature(): string
    {
        $signature = $this->getMerchantPassword();
        $signature .= $this->getMerchantId();
        $signature .= $this->getAcquirerId();
        $signature .= $this->getTransactionId();
        $signature .= $this->formatAmount();
        $signature .= $this->getCurrencyNumeric();

        return base64_encode(sha1($signature, true));
    }
}
