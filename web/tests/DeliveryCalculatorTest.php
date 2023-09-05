<?php

use App\DeliveryCalculator;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class DeliveryCalculatorTest extends TestCase {

    public function testCalculateDeliveryCost()
    {
        // Создание заглушки для клиента HTTP
        $client = $this->createMock(Client::class);

        // Задание ожидаемого ответа от HTTP запроса для быстрой доставки
        $fastDeliveryData = [
            'price' => 10.00,
            'date' => '2021-10-01'
        ];
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode($fastDeliveryData));
        $client->method('request')->willReturn($response);

        // Создание экземпляра DeliveryCalculator с заглушкой клиента HTTP
        $calculator = new DeliveryCalculator('http://example.com', $client);

        // Тестирование метода calculate_delivery_cost

        // Подготовка данных отправления
        $deliveries = [
            ['sourceKladr' => 'source1', 'targetKladr' => 'target1', 'weight' => 2],
            ['sourceKladr' => 'source2', 'targetKladr' => 'target2', 'weight' => 3]
        ];

        // Вызов тестируемого метода
        $results = $calculator->calculateDeliveryCost($deliveries);

        // Проверка ожидаемых результатов
        $this->assertEquals(2, count($results));
        $this->assertEquals(10.00, $results[0]['price']);
        $this->assertEquals('2021-10-01', $results[0]['date']);
        $this->assertNull($results[0]['error']);
        $this->assertNull($results[1]['price']);
        $this->assertNull($results[1]['date']);
        $this->assertNull($results[1]['error']);
    }

    public function testValidateDeliveryData()
    {
        // Создание экземпляра DeliveryCalculator
        $calculator = new DeliveryCalculator('http://example.com', new Client());

        // Проверка корректных данных
        $data = ['sourceKladr' => 'source', 'targetKladr' => 'target', 'weight' => 1];
        $this->assertTrue($calculator->validateDeliveryData($data));

        // Проверка некорректных данных
        $data = [];
        $this->assertFalse($calculator->validateDeliveryData($data));

        $data = ['sourceKladr' => '', 'targetKladr' => 'target', 'weight' => 1];
        $this->assertFalse($calculator->validateDeliveryData($data));
    }
}