<?php
namespace Unit;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class TransactionTest extends TestCase
{
    const TRANSACTION_FILE = 'Controller\Transaction';

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testCalculate(): void
    {
        $clasRef = $this->getReflect(self::TRANSACTION_FILE);
        $classRefInstance = $clasRef->newInstanceWithoutConstructor();

        $request = self::getMockBuilder(self::TRANSACTION_FILE)
            ->disableOriginalConstructor()
            ->onlyMethods(['request'])
            ->getMock();
        $request->expects(self::any())
            ->method('request')
            ->willReturn([
                'success' => true,
                'timestamp' => 1519296206,
                'base' => 'EUR',
                'date' => '2021-03-17',
                'rates' => [
                    'AUD' => 1.566015,
                    'CAD' => 1.560132,
                    'CHF' => 1.154727,
                    'CNY' => 7.827874,
                    'GBP' => 0.882047,
                    'JPY' => 132.360679,
                    'USD' => 1.23396,
                    'EUR' => 1
                ],
                'body' => [
                    'country' => [
                        'alpha2' => 'DK',
                    ]
                ]

            ]);

        $result = $classRefInstance->calculate($_SERVER['PWD'] . '/input.txt');

        $this->assertIsArray($result);
    }

    /**
     * @param string $name
     * @return ReflectionClass
     * @throws ReflectionException
     */
    public function getReflect(string $name = ''): ReflectionClass
    {
        return new ReflectionClass($name);
    }
}
