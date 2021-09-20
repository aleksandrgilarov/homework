<?php

namespace App\Service;

use App\Service\interface\AddressInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressService implements AddressInterface
{

    public function __construct(
        private HttpClientInterface $client,
        private ParameterBagInterface $parameterBag,
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * Return address coordinates by IP address
     */
    public function getAddressByIp($ip): ?array
    {
        try {
            return  $this->client
                ->request(
                    'GET',
                    $this->parameterBag->get('address_api_url') . $ip,
                    [
                        'query' => [
                            'access_key' => $this->parameterBag->get('address_api_key'),
                            'fields' => 'city,country_code'
                        ]
                    ]
                )->toArray();
        } catch (TransportExceptionInterface $e) {
            $this->logger->alert($e->getMessage());

            return null;
        }

    }
}