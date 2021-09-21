<?php

namespace App\Controller;

use App\Service\interface\AddressInterface;
use App\Service\interface\IpInterface;
use App\Service\interface\WeatherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @var FilesystemAdapter
     */
    private FilesystemAdapter $cache;

    public function __construct(
        private AddressInterface $addressService,
        private WeatherInterface $openWeatherService,
        private IpInterface $ipService,
    )
    {
        $this->cache = new FilesystemAdapter();
    }

    #[Route("/", name: "main")]
    public function main(): Response
    {
        $ip = $this->ipService->getIp();

        if (!$ip) {
            return new Response("Oops, something went wrong :(", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $addressData = $this->addressService->getLocationByIp($ip);

        if (!$addressData) {
            return new Response("Oops, something went wrong :(", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $cacheKey = $addressData->getIp() . '_' . $addressData->getCity() . '_' . $addressData->getCountry();

        $weather = $this->cache->getItem($cacheKey);

        if (!$weather->isHit()) {
            $weather->set($this->openWeatherService->getAddressWeatherData($addressData));
            $this->cache->save($weather);
        }

        return $this->json($weather->get());
    }

    #[Route("/nocache", name: "nocache")]
    public function nocache(): Response
    {
        $ip = $this->ipService->getIp();

        if (!$ip) {
            return new Response("Oops, something went wrong :(", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $addressData = $this->addressService->getLocationByIp($ip);

        if (!$addressData) {
            return new Response("Oops, something went wrong :(", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json($this->openWeatherService->getAddressWeatherData($addressData));
    }
}