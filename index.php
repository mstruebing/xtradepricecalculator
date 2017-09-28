<?php

const IRON_DEFAULT_PRICE = 15;
const WATER_DEFAULT_PRICE = 20;
const FOOD_DEFAULT_PRICE = 20;
const STEEL_DEFAULT_PRICE = 70;
const ELECTRONICS_DEFAULT_PRICE = 180;

const EULER = 2.7182818284590452354;

function calculate(int $amount, int $defaultPrice) : int {
    return round(1 + (($defaultPrice - 1) * EULER  ^ (-0.01 * $amount)));
}

if (isset($_GET['iron']) && isset($_GET['water']) && isset($_GET['food']) && isset($_GET['steel']) && isset($_GET['electronics'])) {
    $ironPrice = calculate($_GET['iron'], IRON_DEFAULT_PRICE);
    $waterPrice = calculate($_GET['water'], WATER_DEFAULT_PRICE);
    $foodPrice = calculate($_GET['food'], FOOD_DEFAULT_PRICE);
    $steelPrice = calculate($_GET['steel'], STEEL_DEFAULT_PRICE);
    $electronicsPrice = calculate($_GET['electronics'], ELECTRONICS_DEFAULT_PRICE);

    printf ("iron: %s, water; %s, food: %s, steel: %s, electronics: %s\n", $ironPrice, $waterPrice, $foodPrice, $steelPrice, $electronicsPrice);
}
