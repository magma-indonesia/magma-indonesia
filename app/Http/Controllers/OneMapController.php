<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OneMapController extends Controller
{
    /**
     * Get Payloads to send
     *
     * @return array
     */
    public function payloads(): array
    {
        return [
            'username' => config('services.one-map.username'),
            'password' => config('services.one-map.password'),
            'f' => config('services.one-map.format'),
            'expiration' => config('services.one-map.expiration'),
            'client' => config('services.one-map.client'),
            'referer' => config('services.one-map.referer'),
        ];
    }

    /**
     * Return JSON response
     *
     * @return mixed
     */
    public function response(): mixed
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        $response = $client->request('POST', config('services.one-map.url'), [
            'form_params' => $this->payloads()
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get Token
     *
     * @return string
     */
    public function token(): string
    {
        $response = Cache::remember('one-map-token', 60, function () {
            return $this->response();
        });

        return $response['token'];
    }

    /**
     * Get REST API GIS Services
     *
     * @return array
     */
    public function index(): array
    {
        $client = new Client([
            'headers' => [
                'Host' => 'geoportal.esdm.go.id',
                'Authorization' => "Bearer {$this->token()}",
            ]
        ]);

        $response = $client->request(
            'GET',
            'https://geoportal.esdm.go.id/gis4/rest/services/bgl_bgv_gt/Prakiraan_Gerakan_Tanah_Bulanan/MapServer/0', [
            'query' => [
                'f' => 'pjson'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
