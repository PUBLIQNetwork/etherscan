<?php

use EtherScan\EtherScan;
use EtherScan\Modules\Account;
use EtherScan\Resources\ApiConnector;

require __DIR__ . '/../vendor/autoload.php';

$esApiConnector = new ApiConnector('your_api_key');
$etherScan = new EtherScan($esApiConnector);

$onResponse = function ($responseOnResolve, $context) {
    echo 'Called on resolve: ' . $responseOnResolve . PHP_EOL;
    echo 'Called on context: ' . print_r($context, true) . PHP_EOL;
};
$onError = function ($responseOnResolve, $context) {
    echo 'Called on error: ' . $responseOnResolve . PHP_EOL;
};
$account = $etherScan->getAccount(EtherScan::PREFIX_API);
$startT = microtime(1);

$etherScan->callGroupAsync([
    [
        $account->getTransactionsLink('0xbb9bc244d798123fde783fcc1c72d3bb8c189413', 1, 25, Account::SORT_DESC),
        $onResponse, $onError, ['Any data type you want to be passed as second argument to the callbacks. This is optional.']
    ],
    [
        $account->getTransactionsLink('0xbb9bc244d798123fde783fcc1c72d3bb8c189413', 1, 25, Account::SORT_DESC),
        $onResponse, $onError
    ],
    [
        $account->getBalanceLink('0xbb9bc244d798123fde783fcc1c72d3bb8c189413'),
        $onResponse, $onError
    ],
    [
        $account->getTransactionsLink('0xbb9bc244d798123fde783fcc1c72d3bb8c189413', 1, 25, Account::SORT_DESC),
        $onResponse, $onError
    ],
]);

$endT = microtime(1);

echo "DONE IN: " . ($endT - $startT);
