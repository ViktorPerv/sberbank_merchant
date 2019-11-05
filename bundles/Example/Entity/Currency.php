<?php

namespace App\PlainDirectory\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

/**
 * @Table(name="directory_currency")
 * @ORM\Entity(repositoryClass="App\PlainDirectory\Repository\CurrencyRepository")
 */
class Currency
{
    const DEFAULT_CURRENCY_CODE = 'RUB';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $code;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code ?: self::DEFAULT_CURRENCY_CODE;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
