<?php declare(strict_types=1);

namespace SimonPrinz\Tests;

use Exception;
use Generator;
use PHPUnit\Framework\TestCase;
use SimonPrinz\MistQL;

/**
 * @covers \SimonPrinz\MistQL
 */
class SharedTest extends TestCase
{
    /**
     * @dataProvider queryProvider
     */
    public function testQuery(string $query, mixed $data, mixed $expected, bool $throws): void
    {
        if ($throws)
            $this->expectException(Exception::class);

        $result = MistQL::query($query, $data);
        self::assertEquals($expected, $result);
    }

    public static function queryProvider(): Generator
    {
        $testData = json_decode(file_get_contents(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'shared', 'testdata.json'])), true);

        $i = 0;
        foreach ($testData['data'] as $data) {
            foreach ($data['cases'] as $case) {
                foreach ($case['cases'] as $innerCase) {
                    foreach ($innerCase['assertions'] as $assertion) {
                        yield sprintf('#%d %s -> %s -> %s',
                            $i + 1,
                            $data['describe'],
                            $case['describe'],
                            $innerCase['it']) => [
                            $assertion['query'],
                            $assertion['data'],
                            $assertion['expected'] ?? null,
                            $assertion['throws'] ?? false,
                        ];
                        $i++;
                    }
                }
            }
        }
    }
}
