<?php


namespace EMerchant\MerchantBundle\Interfaces;


interface MerchantInterface
{

    /**
     * @param int $summ
     *
     * @return self
     */
    public function setOrderSum(int $summ): self ;

    /**
     * @param string $orderNum
     *
     * @return self
     */
    public function setOrderNumber(string $orderNum): self;

    /**
     * @param string $description
     *
     * @return MerchantInterface
     */
    public function setOrderDescription(string $description): self;

    /**
     * @param string $url
     *
     * @return MerchantInterface
     */
    public function setReturnUrl(string $url): self;

}