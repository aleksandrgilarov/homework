<?php

namespace App\Service;

use App\Entity\IpLocation;
use App\Repository\IpLocationRepository;
use App\Service\interface\AddressInterface;
use Doctrine\ORM\EntityManagerInterface;
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
        private EntityManagerInterface $entityManager,
        private IpLocationRepository $ipRepo,
    )
    {
    }

    /**
     * Return address coordinates by IP address
     */
    public function getLocationByIp($ip): ?IpLocation
    {
        $address = $this->ipRepo->findOneByIp($ip);
        if (!$address) {
            try {
                $response = $this->client
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

                return $this->saveIpLocation($ip, $response);
            } catch (TransportExceptionInterface $e) {
                $this->logger->alert($e->getMessage());

                return null;
            }
        }
        return $address;
    }

    private function saveIpLocation(string $ip, array $location): IpLocation
    {
        $ipLocation = new IpLocation();
        $ipLocation->setIp($ip);
        $ipLocation->setCity($location['city']);
        $ipLocation->setCountry($location['country_code']);

        $this->entityManager->persist($ipLocation);
        $this->entityManager->flush();

        return $ipLocation;
    }
}