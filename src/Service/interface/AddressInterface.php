<?php

namespace App\Service\interface;

use App\Entity\IpLocation;

interface AddressInterface
{
    /**
     * @param string $ip
     * @return IpLocation|null
     */
    public function getLocationByIp(string $ip): ?IpLocation;
}