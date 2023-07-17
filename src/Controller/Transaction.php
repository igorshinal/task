<?php

namespace Controller;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Traits\Request;
use TransactionInterface;

class Transaction implements TransactionInterface
{
    use Request;

    /**
     * @var array
     */
    protected array $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function calculate(string $argv): array
    {
        $schema = [
            'bin',
            'amount',
            'currency'
        ];

        $output = explode("\n", file_get_contents($argv));
        $result = [];

        foreach ($output as $row) {
            $data = json_decode($row, true);
            $compare = array_diff($schema, array_keys($data));
            if (count($compare) > 0) {
                throw new Exception('Schema not valid');
            }

            $binResponse = $this->request($_ENV['BIN_URL'] . $data['bin']);
            $checkState = in_array($binResponse['body']['country']['alpha2'], $this->config['states']);
            $rate = $this->request($_ENV['EXCHANGE_RATES_API'], $_ENV['EXCHANGE_RATES_TOKEN']);
            $commission = $checkState ? 0.01 : 0.02;

            if ($data['currency'] === 'EUR') {
                $result[] = round(intval($data['amount']) * $commission, 2);
            }elseif ($rate['rates'][$data['currency']] > 0) {
                $result[] = round((intval($data['amount']) / $rate['rates'][$data['currency']]) * $commission, 2);
            }
        }

        return $result;
    }
}




