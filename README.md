# API для работы со Sberbank API
---

## Общие сведения
---

+ Все объекты для сервисов можно переопределить (отнаследовать с реализацией соответствующего интерфейса)
    **Например**: своя реализация объекта Message должна реализовывать MessageInterface
+ Работает через библиотеку cUrl
+ Поддерживаются цепочки вызовов методов (в объектах)
+ Поддерживается автоматическая инъекция зависимостей сервисов (autowiring)
+ В случае внутренних ошибок будут выкинуты соответствующие исключения (ошибка 500)

## Настройка
---

### Файл настройки

config\packages\sberbank_api.yaml
```
sberbank:
  # Логин аккаунта
  login: login
  # Пароль аккаунта
  password: 123
  endpoint: https://3dsec.sberbank.ru
```

Для работы необходимо иметь активную учетную запись.
Для теста можно использоать номер карты из официальной документации Сбербанка

```$xslt
    5555 5555 5555 5599
    exp: 12/19 
    cvv 123
```

### Пример использования

```$xslt
    $marchant = new Merchant($payment, $this->router->generate('success_payment',  [], UrlGeneratorInterface::ABSOLUTE_URL));
    $result = $this->merchantService->registerOrder($merchant);

    if ($result->getErrorCode() == '0' && !is_null($result->getData()['formUrl'])) {
        return $this->redirect($result->getData()['formUrl']);
    }

```

