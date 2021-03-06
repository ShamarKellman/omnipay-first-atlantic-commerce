<?php

namespace Omnipay\FirstAtlanticCommerce\Traits;

trait ParameterTrait
{
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantPassword($value)
    {
        return $this->setParameter('merchantPassword', $value);
    }

    public function getMerchantPassword()
    {
        return $this->getParameter('merchantPassword');
    }

    public function setAcquirerId($value)
    {
        return $this->setParameter('acquirerId', $value);
    }

    public function getAcquirerId()
    {
        return $this->getParameter('acquirerId');
    }
}
