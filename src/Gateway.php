<?php

namespace Omnipay\FirstAtlanticCommerce;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\FirstAtlanticCommerce\Enums\ModificationType;
use Omnipay\FirstAtlanticCommerce\Message\Requests\AuthorizeRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\CreateCardRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\HostedPagePreprocessRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\HostedPageResultsRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\PurchaseRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\TransactionModificationRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\TransactionStatusRequest;
use Omnipay\FirstAtlanticCommerce\Message\Requests\UpdateCardRequest;
use Omnipay\FirstAtlanticCommerce\Traits\ParameterTrait;

/**
 * @method NotificationInterface acceptNotification(array $options = array())
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface completePurchase(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway implements GatewayInterface
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

    public function purchase(array $options = []): RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function capture(array $options = []): RequestInterface
    {
        $options['modificationType'] = ModificationType::CAPTURE;

        return $this->createRequest(TransactionModificationRequest::class, $options);
    }

    public function refund(array $options = []): RequestInterface
    {
        $options['modificationType'] = ModificationType::REFUND;

        return $this->createRequest(TransactionModificationRequest::class, $options);
    }

    public function void(array $options = []): RequestInterface
    {
        $options['modificationType'] = ModificationType::REVERSAL;

        return $this->createRequest(TransactionModificationRequest::class, $options);
    }

    public function hostedPage(array $options = []): RequestInterface
    {
        return $this->createRequest(HostedPagePreprocessRequest::class, $options);
    }

    public function hostedPageResult(array $options = []): RequestInterface
    {
        return $this->createRequest(HostedPageResultsRequest::class, $options);
    }

    public function fetchTransaction(array $options = []): RequestInterface
    {
        return $this->createRequest(TransactionStatusRequest::class, $options);
    }

    public function createCard(array $options = []): RequestInterface
    {
        return $this->createRequest(CreateCardRequest::class, $options);
    }

    public function updateCard(array $options = []): RequestInterface
    {
        return $this->createRequest(UpdateCardRequest::class, $options);
    }
}
