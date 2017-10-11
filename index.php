<?php

/* Used HTTP status codes */
const HTTP_OK = 200;
const HTTP_BAD_REQUEST = 400;
const HTTP_INTERNAL_SERVER_ERROR = 500;
const HTTP_NOT_IMPLEMENTED = 501;

/* Error messages */
const ERR_NOT_IMPLEMENTED = 'Wrong request method, use GET';
const ERR_BAD_REQUEST = 'You have to call this service with iron, water, food, steel and electronics as numeric values';
const ERR_INTERNAL_SERVER_ERROR = 'Something went wrong code wise';

/* Default prices of different goods */
const DEFAULT_PRICE_IRCON = 15;
const DEFAULT_PRICE_WATER = 20;
const DEFAULT_PRICE_FOOD = 20;
const DEFAULT_PRICE_STEEL = 70;
const DEFAULT_PRICE_ELECTRONICS = 180;

/* Threshold in percent when a good should get a higher price */
const AMOUNT_DIFFERENCE_THRESHOLD = 20;

/* Amount in percent which is a added to the default price */
/* of a good if the AMOUNT_DIFFERENCE_THRESHOLD is hit */
const PRICE_DIFFERENCE_MULTIPLIER = 20;

/* Multiplier to use for the calculation of the price */
const MULTIPLIER = -0.01;

/**
 * Prints an error message
 *
 * @param string $errorMsg - the error message to print
 * @return void
 */
function printError(string $errorMsg)
{
    printf("ERROR: %s\n", $errorMsg);
}

/**
 * Sets the HTTP response code and prints an error message
 *
 * @param int $httpStatusCode - the http status code to set
 * @param string $errorMsg - the error message to print
 * @return void
 */
function error(int $httpStatusCode, string $errorMsg)
{
    http_response_code($httpStatusCode);
    printError($errorMsg);
}

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
        'iron' => DEFAULT_PRICE_IRON,
        'water' => DEFAULT_PRICE_WATER,
        'food' => DEFAULT_PRICE_FOOD,
        'steel' => DEFAULT_PRICE_STEEL,
        'electronics' => DEFAULT_PRICE_ELECTRONICS
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
        if ($amount < max($amounts) / 100 * (100 - AMOUNT_DIFFERENCE_THRESHOLD)) {
            $calculatedPrices[$type] = $defaultPrices[$type] / 100 * (100 + PRICE_DIFFERENCE_MULTIPLIER);
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
        error(HTTP_NOT_IMPLEMENTED, ERR_NOT_IMPLEMENTED);
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
            error(HTTP_INTERNAL_SERVER_ERROR, ERR_INTERNAL_SERVER_ERROR);
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
        error(HTTP_BAD_REQUEST, ERR_BAD_REQUEST);
        return;
    }

    http_response_code(HTTP_OK);
}

main($_GET);
