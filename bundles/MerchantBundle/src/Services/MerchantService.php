<?php


namespace EMerchant\MerchantBundle\Services;


use App\Payment\Entity\Payment;
use EMerchant\MerchantBundle\Models\Resulter;
use EMerchant\MerchantBundle\Models\Merchant;

class MerchantService extends AbstractBaseService
{

    const DEFAULT_ENDPOINT = '/payment/rest/';

    const REGISTER_ORDER = 'register.do';

    const GET_ORDER_STATUS_EXTENDED = 'getOrderStatusExtended.do';

    public function __construct(string $login = '', string $password = '', string $endpoint = '')
    {
        parent::__construct($login, $password, $endpoint);

    }

    /**
     * Регистрация пополнения счета
     *
     * @param Merchant $merchant
     *
     * @return Resulter
     */
    public function registerOrder(Merchant $merchant): Resulter
    {
        $query = $this->getRegisterQuery($merchant);
        $url = $this->getUrl() . self::REGISTER_ORDER;

        $this->resulter->process($this->sendPost($url, $query));

        return $this->resulter;
    }

    public function getOrderStatus(Payment $payment): Resulter
    {
        $query = $this->getStatusQuery($payment);
        $url = $this->getUrl() . self::GET_ORDER_STATUS_EXTENDED;

        $this->resulter->process($this->sendPost($url, $query));

        return $this->resulter;
    }


}