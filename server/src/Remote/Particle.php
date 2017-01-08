<?php

namespace ChauffeMarcel\Remote;

class Particle
{
    const API_ENDPOINT = 'https://api.particle.io/v1/devices/%s/%s?access_token=%s';

    private $deviceId;
    private $accessToken;

    public function __construct(string $deviceId, string $accessToken)
    {
        $this->deviceId = $deviceId;
        $this->accessToken = $accessToken;
    }

    public function enable()
    {
        $this->callFunction('enable');
    }

    public function disable()
    {
        $this->callFunction('disable');
    }

    public function isHeating(): bool
    {
        return $this->getVariable('status') === true;
    }

    private function callFunction(string $name)
    {
        $result = $this->call($name, 'POST');

        if (empty($result['return_value'])) {
            throw new ParticleLogicException($name, $result);
        }
    }

    private function getVariable(string $name)
    {
        $result = $this->call($name, 'GET');

        if (!isset($result['cmd']) || $result['cmd'] !== 'VarReturn' || !array_key_exists('result', $result)) {
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
