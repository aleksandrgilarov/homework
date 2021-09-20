<?php

namespace App\Service\interface;

interface WeatherInterface
{
    /**
     * @param array $address
     * @return array|null
     */
    public function getAddressWeatherData(array $address): ?array;
}