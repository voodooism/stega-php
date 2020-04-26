<?php

declare(strict_types=1);

namespace Voodooism\Stega\Tests;

use ParagonIE\SeedSpring\SeedSpring;
use PHPUnit\Framework\TestCase;

class TestTest extends TestCase
{
    public function testTest(): void
    {
        $seed = md5('password', true);

        $test = strlen($seed);

        $prng = new SeedSpring($seed);

        $result = [];
        while(count($result) !== 100) {
            $key = $prng->getInt(0, 1000);

            if (!isset($result[$key])) {
                $result[$key] = true;
            }

            $array = array_unique(array_keys($result));
        }

        $this->assertTrue(true);
    }


    public function testSplit(): void
    {
        $array = [
            0 => 1,
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 1,
            7 => 1,
            8 => 1,
            9 => 1,
            11 => 1,
            15 => 1,
            16 => 1,
            17 => 1,
            18 => 1,
            22 => 1,
            26 => 1
        ];

        $expectedResult = [
            0 => [0, 1, 2],
            1 => [3, 4, null],
            2 => [null, 7, 8],
            3 => [9, null, 11],
            5 => [15, 16, 17],
            6 => [18, null, null],
            7 => [null, 22, null],
            8 => [null, null, 26],
        ];

        $result = [];

        foreach (array_keys($array) as $value) {
            $chunkKey = (int)floor($value/3);

            $result[$chunkKey] = $result[$chunkKey] ?? [null, null, null];
            $result[$chunkKey][$value % 3] = $value;
        }

        $this->assertEquals($expectedResult, $result);
    }
}