<?php

namespace Omnipay\OmnipayFirstAtlanticCommerce;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\OmnipayFirstAtlanticCommerce\Enums\ModificationType;
use Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests\AuthorizeRequest;
use Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests\TransactionModificationRequest;
use Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests\HostedPagePreprocessRequest;
use Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests\HostedPageResultsRequest;
use Omnipay\OmnipayFirstAtlanticCommerce\Message\Requests\PurchaseRequest;
use Omnipay\OmnipayFirstAtlanticCommerce\Traits\ParameterTrait;

/**
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface completePurchase(array $options = array())
 * @method RequestInterface refund(array $options = array())
 * @method RequestInterface fetchTransaction(array $options = [])
 * @method RequestInterface void(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
    use ParameterTrait;

    public function getName(): string
    {
        return 'First Atlantic Commerce.';
    }

    public function getShortName(): string
    {
        return 'FAC';
    }

    public function getDefaultParameters(): array
    {
        return [
            'merchantId' => null,
            'merchantPassword' => null,
            'acquirerId' => '464748',
            'testMode' => false,
        ];
    }

    public function authorize(array $options = []): RequestInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    public function purchase(array $options): RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function capture(array $options): RequestInterface
    {
        $options['modificationType'] = ModificationType::CAPTURE;
        return $this->createRequest(TransactionModificationRequest::class, $options);
    }

    public function hostedPage(array $options): RequestInterface
    {
        return $this->createRequest(HostedPagePreprocessRequest::class, $options);
    }

    public function hostedPageResult(array $options): RequestInterface
    {
        return $this->createRequest(HostedPageResultsRequest::class, $options);
    }
}
