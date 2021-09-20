<?php

namespace App\Service\interface;

interface AddressInterface
{
    /**
     * @param string $ip
     * @return array|null
     */
    public function getAddressByIp(string $ip): ?array;
}