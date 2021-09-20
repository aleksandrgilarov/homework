<?php

namespace App\Service\interface;

use App\Entity\IpLocation;

interface WeatherInterface
{
    /**
     * @param IpLocation $address
     * @return array|null
     */
    public function getAddressWeatherData(IpLocation $address): ?array;
}