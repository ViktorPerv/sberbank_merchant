<?php

namespace EMerchant\MerchantBundle\Services;

use App\Payment\Entity\Payment;
use EMerchant\MerchantBundle\Interfaces\ResulterInterface;
use EMerchant\MerchantBundle\Models\Merchant;
use EMerchant\MerchantBundle\Models\Resulter;
use Symfony\Component\Config\Definition\Exception\Exception;

abstract class AbstractBaseService
{
    const DEFAULT_ENDPOINT = '/payment/rest/';

    /**
     * Время по умолчанию на выполнение запроса
     * в секундах
     */
    const TIMEOUT_DEFAULT = 60;

    /**
     * Время по умолчанию на соединение с сервером
     * в секундах
     */
    const CONNECTION_TIMEOUT_DEFAULT = 5;

    /**
     * Логин аккаунта
     *
     * @var string
     */
    protected $login = '';

    /**
     * Пароль аккаунта
     *
     * @var string
     */
    protected $password = '';

    /**
     * Точка входа для мерчанта
     *
     * @var string
     */
    protected $endpoint = '';


    /**
     * Объект - результат запроса
     *
     * @var ResulterInterface
     */
    protected $resulter;

    /**
     * Максимальное время на соединение с сервером
     * в секундах
     *
     * @var integer
     */
    protected $connectionTimeout = self::CONNECTION_TIMEOUT_DEFAULT;

    /**
     * Максимальное время на выполнение запроса
     * в секундах
     *
     * @var integer
     */
    protected $timeout = self::TIMEOUT_DEFAULT;

    public function __construct(string $login = '', string $password = '', string $endpoint = '', ?Resulter $resulter = null)
    {
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setEndpoint($endpoint);

        $this->resulter = $resulter ?? new Resulter();
    }

    /**
     * Возвращает логин аккаунта
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Устанавливает логин аккаунта
     * Если передан пустой логин, то текущий логин останется без изменений
     *
     * @param string $login
     *
     * @return \EMerchant\MerchantBundle\Services\AbstractBaseService
     */
    public function setLogin(string $login = ''): self
    {
        if (!empty($login)) {
            $this->login = $login;
        }

        return $this;
    }

    /**
     * Возвращает пароль аккаунта
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Устанавливает пароль аккаунта
     * Если передан пустой пароль, то текущий пароль останется без изменений
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password = ''): self
    {
        if (!empty($password)) {
            $this->password = $password;
        }

        return $this;
    }

    /**
     * Возвращает точку входа
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Устанавливает точку входа
     * Если передана пустая точка входа, то текущая точка входа останется без изменений
     *
     * @param string $endpoint
     *
     * @return self
     */
    public function setEndpoint(string $endpoint = ''): self
    {
        if (!empty($endpoint)) {
            $this->endpoint = $endpoint;
        }

        return $this;
    }

    /**
     * Возвращает URL сервиса
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getEndpoint() . self::DEFAULT_ENDPOINT;
    }

    /**
     * Делаем запрос на данные мерчанта
     *
     * @param string $url
     * @param array $query
     *
     * @return string
     */
    public function sendPost(string $url = '', array $query = []): string
    {
        $query = http_build_query($query);
        return $this->sendRequest($url, $query);
    }

    /**
     * Общий метод отправки запроса на сервис
     * работает через cURL
     *
     * @param string $url
     * @param string $query
     *
     * @return string|bool
     */
    private function sendRequest(string $url = '', string $query = '')
    {

        if (empty($url) || empty($query)) {
            throw new Exception('Неверно указан URL запроса!', 500);
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->connectionTimeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ["Expect:"],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $query,
            CURLOPT_URL => $url
        ]);

        $curlResult = curl_exec($curl);
        curl_close($curl);

        return $curlResult;
    }

    /**
     * Возвращает максимальное время соединения с сервером
     *
     * @return integer
     */
    public function getConnectionTimeout(): int
    {
        return $this->connectionTimeout;
    }

    /**
     * Устанавливает максимальное время соединения с сервером
     * Новое время устанавливается только если оно больше или равно нулю
     *
     * @param integer $timeout
     * @return \EMerchant\MerchantBundle\Services\AbstractBaseService
     */
    public function setConnectionTimeout(int $timeout = 0): self
    {
        if ($timeout >= 0) {
            $this->connectionTimeout = $timeout;
        }

        return $this;
    }

    /**
     * Возвращает максимальное время выполнения запроса
     *
     * @return integer
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Устанавливает максимальное время выполнения запроса
     * Новое время устанавливается только если оно больше или равно нулю
     *
     * @param integer $timeout
     *
     * @return self
     */
    public function setTimeout(int $timeout = 0): self
    {
        if ($timeout >= 0) {
            $this->timeout = $timeout;
        }

        return $this;
    }

    /**
     * @param Merchant $merchant
     *
     * @return array
     */
    public function getRegisterQuery(Merchant $merchant):array
    {
        return [
            'userName' => $this->getLogin(),
            'password' => $this->getPassword(),
            'amount' => $merchant->getOrderSum(),
            'orderNumber' => $merchant->getOrderNumber(),
            'returnUrl' => $merchant->getReturnUrl()
        ];
    }

    /**
     * @param Payment $payment
     *
     * @return array
     */
    public function getStatusQuery(Payment $payment):array
    {
        return [
            'userName' => $this->getLogin(),
            'password' => $this->getPassword(),
            'orderId' => $payment->getTransaction()
        ];
    }

}