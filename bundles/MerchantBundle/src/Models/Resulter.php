<?php

namespace EMerchant\MerchantBundle\Models;

use EMerchant\MerchantBundle\Interfaces\ResulterInterface;

/**
 * Результат запроса
 * Обрабатывает ответ от сервиса
 */
class Resulter implements ResulterInterface
{
    /**
     * Статус результата
     * Ошибка/Успех
     *
     * @var int
     */
    private $status = self::STATUS_UNKNOWN;

    /**
     * Код ошибки
     *
     * @var int
     */
    private $errorCode = 0;

    /**
     * Cообщение ошибки
     *
     * @var string
     */
    private $errorText = '';

    /**
     * Массив остальных данных
     *
     * @var array
     */
    private $data = [];

    /**
     * @inheritDoc
     */
    public function process(string $result): ResulterInterface
    {
        $resultObject = json_decode($result, true);

        // Проверка статуса результата запроса
        if (isset($resultObject['errorCode'])) {
            // Обрабатываем данные об ошибке
            $this->status = ResulterInterface::STATUS_ERROR;
            $this->errorCode = $resultObject['errorCode'];
            $this->errorText = $resultObject['errorMessage'];
        } else {
            $this->status = ResulterInterface::STATUS_SUCCESS;
        }

        // Убираем ненужные поля
        if (isset($resultObject['errorCode'])) {
            unset($resultObject['errorCode']);
        }


        $this->data = $resultObject;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @inheritDoc
     */
    public function getErrorText(): string
    {
        return $this->errorText;
    }

    /**
     * @inheritDoc
     */
    public function getData(): ?array
    {
        return $this->data;
    }
}
