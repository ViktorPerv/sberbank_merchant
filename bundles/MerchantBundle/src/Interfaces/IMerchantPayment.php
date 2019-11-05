<?php

namespace EMerchant\MerchantBundle\Interfaces;

interface IMerchantPayment
{

    /**
     * Получить имя(id) платежной системы
     * @access public
     * @return string
     */
    public function getName();

    public function getTitle();

    /**
     * Получить наименование плажной системы, через которую совершается платеж, например МТС, Beeline и т.п
     * @access public
     * @param \App\Payment\Entity\Payment $operation
     * @return string
     */
    public function getPaymentMode(\App\Payment\Entity\Payment $operation);

    /**
     * Установить сумму платежа
     * @access public
     * @param double $amount сумма платежа
     * @return \IPayment
     */
    public function setAmount($amount);

    /**
     * Установить номер заказа
     * @access public
     * @param integer $id номер заказа
     * @return \IPayment
     */
    public function setOrder($id);

    /**
     * Установить описание заказа
     * @access public
     * @param string $description описание
     * @return \IPayment
     */
    public function setDescription($description);

    /**
     * Проверка существования заказа
     * @param array $params
     * @return integer <pre>0 заказ существует<br>1 - код проверки не верен<br>100 - заказ неверный<br>200 - заказ не найден</pre>
     */
    public function billExists(array $params);

    /**
     * Возвращает данные для редиректа на страницу оплаты
     * @access public
     * @param \App\Payment\Entity\Payment $model
     * @return array|string
     */
    public function redirect(\App\Payment\Entity\Payment $model);

    /**
     * Получить состояние оплаты счета
     * @param \App\Payment\Entity\Payment $operation операция пополнения
     * @return integer  код текущего состояния операции оплаты счета
     */
    public function getStatus(\App\Payment\Entity\Payment $operation);


}
