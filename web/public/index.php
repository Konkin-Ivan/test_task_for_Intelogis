<?php

use App\ApiEmulator;
use App\DeliveryCalculator;
use GuzzleHttp\Client;

require_once dirname(__DIR__) . "/vendor/autoload.php";

// Создаем клиент GuzzleHttp с настроенным HandlerStack
$handlerStack = ApiEmulator::createHandlerStack();
$client = new Client(['handler' => $handlerStack]);
$baseUrl = 'https://example.com';  // Базовый URL для API доставки

$deliveries = [
    [
        'sourceKladr' => 'kladr1',
        'targetKladr' => 'kladr2',
        'weight' => 1.5
    ],
    [
        'sourceKladr' => 'kladr3',
        'targetKladr' => 'kladr4',
        'weight' => 2.0
    ]
];

// Передаем созданный клиент в конструктор DeliveryCalculator
$calculator = new DeliveryCalculator($baseUrl, $client);
$results = $calculator->calculateDeliveryCost($deliveries);

// Возвращаем результат
foreach ($results as $result) {
    print_r($result);
}