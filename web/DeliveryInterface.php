<?php

namespace App;

interface DeliveryInterface
{
    public function validateDeliveryData($data);
    public function calculateFastDeliveryCost($data);
    public function calculateSlowDeliveryCost($data);
}