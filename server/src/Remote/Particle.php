<?php

namespace App\Remote;

class Particle
{
    const API_ENDPOINT = 'https://api.particle.io/v1/devices/%s/%s?access_token=%s';

    private string $deviceId;
    private string $accessToken;

    public function __construct(string $particleDeviceId, string $particleAccessToken)
    {
        $this->deviceId = $particleDeviceId;
        $this->accessToken = $particleAccessToken;
    }

    public function enable(): void
    {
        $this->callFunction('enable');
    }

    public function disable(): void
    {
        $this->callFunction('disable');
    }

    public function isHeating(): bool
    {
        return true === $this->getVariable('status');
    }

    private function callFunction(string $name): void
    {
        $result = $this->call($name, 'POST');

        if (empty($result['return_value'])) {
            throw new ParticleLogicException($name, $result);
        }
    }

    private function getVariable(string $name)
    {
        $result = $this->call($name, 'GET');

        if (!isset($result['cmd']) || 'VarReturn' !== $result['cmd'] || !array_key_exists('result', $result)) {
            throw new ParticleLogicException($name, $result);
        }

        return $result['result'];
    }

    private function getUrl(string $endpoint): string
    {
        return sprintf(
            self::API_ENDPOINT,
            $this->deviceId,
            $endpoint,
            $this->accessToken
        );
    }

    private function call($endpoint, string $method): array
    {
        $url = $this->getUrl($endpoint);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (!$result) {
            throw new ParticleApiException($endpoint);
        }

        return $result;
    }
}
