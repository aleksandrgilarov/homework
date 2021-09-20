<?php

namespace App\Service\interface;

interface IpInterface
{
    /**
     * @return string|null
     */
    public function getIp(): ?string;
}