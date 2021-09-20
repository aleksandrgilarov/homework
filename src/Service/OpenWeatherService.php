<?php

namespace App\Service;

use App\Service\interface\WeatherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherService implements WeatherInterface
{

    public function __construct(
        private HttpClientInterface $client,
        private ParameterBagInterface $parameterBag,
        private LoggerInterface $logger,
    )
    {
    }

    public function getAddressWeatherData(array $address): ?array
    {
        try {
            return  $this->client
                ->request(
                    'GET',
                    $this->parameterBag->get('weather_api_url') . 'weather',
                    [
                        'query' => [
                            'q' => $address['city'] . ',' . $address['country_code'],
                            'units' => 'metric',
                            'appid' => $this->parameterBag->get('weather_api_key')
                        ]
                    ]
                )->toArray();
        } catch (TransportExceptionInterface $e) {
            $this->logger->alert($e->getMessage());

            return null;
        }

    }
}