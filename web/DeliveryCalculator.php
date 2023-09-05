<?php

namespace App;

class DeliveryCalculator implements DeliveryInterface
{
    private $baseUrl;
    private $client;

    public function __construct($baseUrl, $client)
    {
        $this->baseUrl = $baseUrl;
        $this->client = $client;
    }

    public function calculateDeliveryCost($deliveries)
    {
        $results = [];
        foreach ($deliveries as $delivery) {
            if (!$this->validateDeliveryData($delivery)) {
                continue;
            }

            $fastDeliveryResult = $this->calculateFastDeliveryCost($delivery);
            $slowDeliveryResult = $this->calculateFastDeliveryCost($delivery);

            $result = [
                'price' => isset($fastDeliveryResult['price']) ? $fastDeliveryResult['price'] : null,
                'date' => isset($slowDeliveryResult['date']) ? $slowDeliveryResult['date'] : null,
                'error' => isset($fastDeliveryResult['error']) ? $fastDeliveryResult['error'] : null
            ];
            $results[] = $result;
        }

        return $results;
    }

    public function validateDeliveryData($data)
    {
        $requiredKeys = ['sourceKladr', 'targetKladr', 'weight'];
        foreach ($requiredKeys as $key) {
            if (!isset($data[$key]) || empty($data[$key])) {
                return false;
            }
        }

        return true;
    }

    public function calculateFastDeliveryCost($data)
    {
        $url = $this->baseUrl . '/fast_delivery';
        try {
            $response = $this->client->request('GET', $url, ['query' => $data]);
            $body = $response->getBody()->getContents();
            return json_decode($body, true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function calculateSlowDeliveryCost($data)
    {
        $url = $this->baseUrl . '/slow_delivery';
        try {
            $response = $this->client->request('GET', $url, ['query' => $data]);
            $body = $response->getBody()->getContents();
            return json_decode($body, true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
