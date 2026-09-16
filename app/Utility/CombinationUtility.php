<?php

namespace App\Utility;

class CombinationUtility
{
    // Cartesian product of the given option arrays, e.g. [['S','M'],['Red']] => [['S','Red'],['M','Red']]
    public static function makeCombinations(array $arrays): array
    {
        $combinations = [[]];

        foreach ($arrays as $array) {
            $append = [];
            foreach ($combinations as $combination) {
                foreach ($array as $value) {
                    $append[] = array_merge($combination, [$value]);
                }
            }
            $combinations = $append;
        }

        return $combinations;
    }
}
