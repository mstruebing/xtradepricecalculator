<?php

/* Used HTTP status codes */
const HTTP_NOT_IMPLEMENTED = 501;
const HTTP_BAD_REQUEST = 400;
const HTTP_OK = 200;

/* Default prices of different goods */
const IRON_DEFAULT_PRICE = 15;
const WATER_DEFAULT_PRICE = 20;
const FOOD_DEFAULT_PRICE = 20;
const STEEL_DEFAULT_PRICE = 70;
const ELECTRONICS_DEFAULT_PRICE = 180;

/* Threshold in percent when a good should get a higher price */
/* currently also used to add amount of percent to the default price */
const THRESHOLD = 20;

/* Multiplier to use for the calculation of the price */
const MULTIPLIER = -0.01;

/**
 * Checks if a request contains all needed information for this service
 *
 * @param array $args - the $_GET variables
 * @return bool - whether the request contains all valid information or not
 */
function isValidRequest(array $args) : bool
{
    return count($args) === 5 &&
        isset($args['iron']) && is_numeric($args['iron']) &&
        isset($args['water']) && is_numeric($args['water']) &&
        isset($args['food']) && is_numeric($args['food']) &&
        isset($args['steel']) && is_numeric($args['steel']) &&
        isset($args['electronics']) && is_numeric($args['electronics']);
}

/**
 * Calculates adjusted default prices out of different amounts of goods
 *
 * @param int $ironAmount
 * @param int $waterAmount
 * @param int $foodAmount
 * @param int $steelAmount
 * @param int $electronicsAmount
 * @return array - an array containing the new calculated prices
 */
function adjustDefaultPrices(
    int $ironAmount,
    int $waterAmount,
    int $foodAmount,
    int $steelAmount,
    int $electronicsAmount
) : array {
    $defaultPrices =  [
        'iron' => IRON_DEFAULT_PRICE,
        'water' => WATER_DEFAULT_PRICE,
        'food' => FOOD_DEFAULT_PRICE,
        'steel' => STEEL_DEFAULT_PRICE,
        'electronics' => ELECTRONICS_DEFAULT_PRICE
    ];

    $amounts = [
        'iron' => $ironAmount,
        'water' => $waterAmount,
        'food' => $foodAmount,
        'steel' => $steelAmount,
        'electronics' => $electronicsAmount
    ];

    $calculatedPrices = [];

    foreach ($amounts as $type => $amount) {
        /* if the amount is below a certrain threshold of the maximum */
        /* it gets a higher default price for the remaining calculation */
        if ($amount < max($amounts) / 100 * (100 - THRESHOLD)) {
            $calculatedPrices[$type] = $defaultPrices[$type] / 100 * (100 + THRESHOLD);
        } else {
            $calculatedPrices[$type] = $defaultPrices[$type];
        }
    }

    return $calculatedPrices;
}

/**
 * Calculates the price of a good
 *
 * @param int $amount - the amount of the good
 * @param int $defaultPrice - the default price for the specific good
 * @return int - the price
 */
function calculatePrice(int $amount, int $defaultPrice) : int
{
    return 1 + (($defaultPrice - 1) * pow(M_E, (MULTIPLIER * $amount)));
}

/**
 * Main function
 *
 * @param array $args - the request arguments
 * @return void
 */
function main(array $args) : void
{

    /* Request have to be a GET request */
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(HTTP_NOT_IMPLEMENTED);
        printf("ERROR: Wrong request method, use GET\n");
        return;
    }

    $args = $_GET;

    if (isValidRequest($args)) {
        $ironAmount = $args['iron'];
        $waterAmount = $args['water'];
        $foodAmount = $args['food'];
        $steelAmount = $args['steel'];
        $electronicsAmount = $args['electronics'];

        $defaultPrices = adjustDefaultPrices($ironAmount, $waterAmount, $foodAmount, $steelAmount, $electronicsAmount);

        if (!isset($defaultPrices['iron']) &&
            !isset($defaultPrices['water']) &&
            !isset($defaultPrices['food']) &&
            !isset($defaultPrices['steel']) &&
            !isset($defaultPrices['electronics'])) {
            printf("ERROR: Something went wrong code wise\n");
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }

        $ironPrice = calculatePrice($ironAmount, $defaultPrices['iron']);
        $waterPrice = calculatePrice($waterAmount, $defaultPrices['water']);
        $foodPrice = calculatePrice($foodAmount, $defaultPrices['food']);
        $steelPrice = calculatePrice($steelAmount, $defaultPrices['steel']);
        $electronicsPrice = calculatePrice($electronicsAmount, $defaultPrices['electronics']);

        printf(
            "iron: %s, water; %s, food: %s, steel: %s, electronics: %s\n",
            $ironPrice,
            $waterPrice,
            $foodPrice,
            $steelPrice,
            $electronicsPrice
        );
    } else {
        printf(
            "ERROR: You have to call this service with iron, water, food, steel and electronics as numeric values\n"
        );
        http_response_code(HTTP_BAD_REQUEST);
        return;
    }

    http_response_code(HTTP_OK);
}

main($_GET);
