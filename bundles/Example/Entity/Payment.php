<?php

declare(strict_types=1);

namespace App\Payment\Entity;

use Entity\Currency;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Payment\Repository\PaymentRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="payment")
 */
class Payment
{
    /**
     * Тип операции - приход
     */
    const OPERATION_INCOME = 1;

    /**
     * Тип операции - расход
     */
    const OPERATION_EXPENSE = 2;

    /**
     * Тип операции - возврат
     */
    const OPERATION_REFUND = 3;

    /**
     * Статус операции - Новая
     */
    const STATUS_NEW = 1;

    /**
     * Статус операции - Выполнена(завершена)
     */
    const STATUS_COMPLETE = 2;

    /**
     * Статус операции - Отменена
     */
    const STATUS_CANCEL = 3;

    /**
     * Статус операции - Возврат
     */
    const STATUS_REFUND = 4;

    /**
     * Статус операции - Отменяем платеж
     */
    const STATUS_REVERSING = 5;

    /**
     * Статус операции - Платеж отменен
     */
    const STATUS_REVERSED = 6;

    /**
     * Количество платежей на одной странице
     */
    const DEFAULT_PAGE_SIZE = 10;

    /**
     * Платеж через банковскую карту
     */
    const SYSTEM_CARD = 'card';

    /**
     * Платеж юр.лицом
     */
    const SYSTEM_LEGAL_ENTITY = 'legal';

    /**
     * Тип платежной системы - Сбербанк
     */
    const SYSTEM_PAYMODE_SBERBANK = 1;


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * номер транзакции в плат. системе
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $transaction;

    /**
     * @ORM\Column(type="money")
     *
     * @var Money
     */
    private $summ;

    /**
     * @ORM\Column(type="integer")
     * @var string
     */
    private $operation;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $status;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     * Наименование платежной системы
     * @var string
     */
    private $system;

    /**
     * @ORM\Column(type="integer")
     * расшифровка от платёжной системы(через что была оплата - Банковская карта, Запрос счета и т.п)
     * @var integer
     */
    private $system_paymode;

    /**
     * @ORM\Column(type="string", nullable=true)
     * uuid платежа
     * @var string
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", nullable=true)
     * Имя держателя карты
     * @var string
     */
    private $cardholdername;

    /**
     * @ORM\Column(type="string", nullable=true)
     * Маскированный номер карты, которая использовалась для оплаты
     * @var string
     */
    private $maskedpan;

    /**
     * @ORM\Column(type="string", nullable=true)
     * Дата действия карты клиенты
     * @var string
     */
    private $expiration;

    /**
     * Many Orders have Many Payments.
     * @ORM\ManyToOne(targetEntity="Orders", inversedBy="payment")
     *
     * @ORM\JoinColumn(name="orders_id", referencedColumnName="id", nullable=true)
     */
    private $order;

    public function __construct()
    {
        $this->summ = new Money('0', (new \Money\Currency(Currency::DEFAULT_CURRENCY_CODE)));
    }

    public function getId(): ?int
    {
        return intval($this->id);
    }

    public function getTransaction(): string
    {
        return $this->transaction;
    }

    public function setTransaction(string $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getSumm(): Money
    {
        return $this->summ;
    }

    /**
     * @param Money $summ
     * @return self
     */
    public function setSumm(Money $summ): self
    {
        $this->summ = $summ;

        return $this;
    }

    public function getOrder(): Orders
    {
        return $this->order;
    }

    public function setOrder(Orders $orders)
    {
        $this->order = $orders;

        return $this;
    }

    /**
     * Получить список операций
     * @access public
     * @return array
     */
    public function getOperationList()
    {

        return array(
            self::OPERATION_INCOME => 'income',
            self::OPERATION_EXPENSE => 'expense',
        );
    }

    /**
     * Получить описание операции
     * @return string
     */
    public function getOperationDescription()
    {

        return $this->getOperationList()[$this->operation];
    }

    /**
     * Получить описание статуса операции
     * @access public
     * @return string
     */
    public function getStatusDescription()
    {
        return $this->getStatusList()[$this->status];
    }

    /**
     * Получить статусы операций
     * @access public
     * @return array
     */
    public function getStatusList()
    {
        return array(
            self::STATUS_NEW => 'Не оплачен',
            self::STATUS_COMPLETE => 'Оплачен',
            self::STATUS_CANCEL => 'Отменен',
            self::STATUS_REFUND => 'Возврат',
            self::STATUS_REVERSING => 'Происходит отмена платежа',
            self::STATUS_REVERSED => 'Платеж отменен',
        );
    }

    /**
     * Проверка, что операция завершена
     * @access public
     * @return boolean  true - завершена, иначе false
     */
    public function isComplete()
    {
        return $this->status == self::STATUS_COMPLETE;
    }


    /**
     * Проверка, что операция отменена
     * @access public
     * @return boolean  true - отменена, иначе false
     */
    public function isCanceled()
    {
        return $this->status == self::STATUS_CANCEL;
    }

    /**
     * Проверка, что платеж отменяется
     * @access public
     * @return boolean  true - отменена, иначе false
     */
    public function isReversing()
    {
        return $this->status == self::STATUS_REVERSING;
    }

    /**
     * Проверка, платеж отменен
     * @access public
     * @return boolean  true - отменена, иначе false
     */
    public function isReversed()
    {
        return $this->status == self::STATUS_REVERSED;
    }

    /**
     * @return int
     */
    public function getOperation(): int
    {
        return intval($this->operation);
    }

    public function setOperation(int $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSystem(): ?string
    {
        return $this->system;
    }

    public function setSystem(?string $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getSystemPaymode(): ?int
    {
        return $this->system_paymode;
    }

    public function setSystemPaymode(?int $system_paymode): self
    {
        $this->system_paymode = $system_paymode;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCardholdername(): ?string
    {
        return $this->cardholdername;
    }

    public function setCardholdername(?string $cardholdername): self
    {
        $this->cardholdername = $cardholdername;

        return $this;
    }

    public function getMaskedpan(): ?string
    {
        return $this->maskedpan;
    }

    public function setMaskedpan(?string $maskedpan): self
    {
        $this->maskedpan = $maskedpan;

        return $this;
    }

    public function getExpiration(): ?string
    {
        return $this->expiration;
    }

    public function setExpiration(?string $expiration): self
    {
        $this->expiration = $expiration;

        return $this;
    }

}
