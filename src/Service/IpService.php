<?php

namespace App\Service;

use App\Service\interface\IpInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IpService implements IpInterface
{

    public function __construct(
        private HttpClientInterface $client,
        private ParameterBagInterface $parameterBag,
        private LoggerInterface $logger,
    )
    {
    }

    public function getIp(): ?string
    {
        try {
            return $this->client->request(
                'GET',
                $this->parameterBag->get('ip_api_url')
            )->getContent();
        } catch (TransportExceptionInterface $e) {
            $this->logger->alert($e->getMessage());

            return null;
        }
    }
}