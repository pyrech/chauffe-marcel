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
        $this->call('enable');
    }

    public function disable()
    {
        $this->call('disable');
    }

    private function call($action)
    {
        $url = sprintf(
            self::API_ENDPOINT,
            $this->deviceId,
            $action,
            $this->accessToken
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!$result || !isset($result->return_value)) {
            throw new ParticleException($action, $result);
        }
    }
}
