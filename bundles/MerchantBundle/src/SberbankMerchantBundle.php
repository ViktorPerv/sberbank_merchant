<?php

namespace EMerchant\MerchantBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use EMerchant\MerchantBundle\DependencyInjection\SberbankApiExtension;

/**
 * Класс бандла
 */
class SberbankMerchantBundle extends Bundle
{
    /**
     * Перегрузка метода, чтобы можно было использовать нестандартный псевдоним для конфигурации бандла
     *
     * @return SberbankApiExtension|ExtensionInterface
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new SberbankApiExtension();
        }
        return $this->extension;
    }
}
