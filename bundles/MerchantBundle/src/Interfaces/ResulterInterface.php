<?php

namespace EMerchant\MerchantBundle\Interfaces;

interface ResulterInterface
{
    /**
     * Статус результата
     * Не определено
     */
    const STATUS_UNKNOWN = -1;

    /**
     * Статус результата
     * Ошибка
     */
    const STATUS_ERROR = 0;

    /**
     * Статус результата
     * Успех
     */
    const STATUS_SUCCESS = 1;

    /**
     * Обработка объекта результата запроса - декодированного JSON
     *
     * @param string $result
     *
     * @return self
     */
    public function process(string $result): self;

    /**
     * Возвращает статус результата запроса Ошибка/Успех
     *
     * @return  int
     */
    public function getStatus(): int;

    /**
     * Возвращает код ошибки
     *
     * @return  int
     */
    public function getErrorCode(): int;

    /**
     * Возвращает cообщение об ошибке
     *
     * @return  string
     */
    public function getErrorText(): string;

    /**
     * Возвращает массив остальных данных
     *
     * @return array
     */
    public function getData(): ?array;
}
