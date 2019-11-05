<?php

namespace EMerchant\MerchantBundle\Models;

use App\Payment\Entity\Payment;
use EMerchant\MerchantBundle\Interfaces\MerchantInterface;

class Merchant implements MerchantInterface
{

    protected $name = 'sberbank';
    protected $title = 'Сбербанк';

    /**
     * Номер заказа
     * @var integer
     * @access protected
     */
    protected $orderNumber;

    /**
     * Описание заказа
     * @var string
     * @access protected
     */
    protected $orderDescription;

    /**
     * Сумма заказа
     * @var float
     * @access protected
     */
    protected $orderSum;


    /**
     * URL для перенаправления, после оповещения об оплате(отказа от оплаты и т.п)
     * @var string
     */
    protected $returnUrl;

    /**
     * @var \App\Payment\Entity\Payment
     */
    protected $payment;

    public function __construct(Payment $payment, string $router)
    {
        $this->payment = $payment;

        $this->setOrderSum($this->payment->getSumm()->getAmount());
        $this->setOrderNumber($this->payment->getId().'-'.time());
        $this->setOrderDescription($this->payment->getDescription() ? $this->payment->getDescription() : '');
        $this->setReturnUrl($router);

    }

    /**
     * @param int $summ
     *
     * @return MerchantInterface
     */
    public function setOrderSum(int $summ): MerchantInterface
    {
        $this->orderSum = $summ;

        return $this;
    }


    public function getOrderSum()
    {
        return $this->orderSum = $this->payment->getSumm()->getAmount();
    }

    /**
     * @param string $order
     * @return MerchantInterface
     */
    public function setOrderNumber(string $order): MerchantInterface
    {
        $this->orderNumber = $order;

        return $this;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * @param string $description
     *
     * @return MerchantInterface
     */
    public function setOrderDescription(string $description): MerchantInterface
    {
        $this->orderDescription = $description;

        return $this;
    }

    public function getOrderDescription()
    {
        return $this->orderDescription;

    }

    /**
     * @param string $url
     *
     * @return MerchantInterface
     */
    public function setReturnUrl(string $url): MerchantInterface
    {
        $this->returnUrl = $url;

        return $this;
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

}