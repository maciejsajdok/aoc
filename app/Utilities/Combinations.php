<?php

declare(strict_types=1);

namespace App\Utilities;

use Generator;

class Combinations
{
    private $_elements = array();

    public function __construct($elements)
    {
        $this->setElements($elements);
    }

    public function setElements($elements){
        $this->_elements = array_values($elements);
    }

    public function getCombinations($length, $with_repetition = false){
        $combinations = array();

        foreach ($this->x_calculateCombinations($length, $with_repetition) as $value){
            $combinations[] = $value;
        }

        return $combinations;
    }

    public function getPermutations($length, $with_repetition = false): array
    {
        $permutations = array();

        foreach ($this->x_calculatePermutations($length, $with_repetition) as $value){
            $permutations[] = $value;
        }

        return $permutations;
    }

    private function x_calculateCombinations($length, $with_repetition = false, $position = 0, $elements = array()): Generator
    {

        $items_count = count($this->_elements);

        for ($i = $position; $i < $items_count; $i++){

            $elements[] = $this->_elements[$i];

            if (count($elements) == $length){
                yield $elements;
            }
            else{
                foreach ($this->x_calculateCombinations($length, $with_repetition, ($with_repetition ? $i : $i + 1), $elements) as $value2){
                    yield $value2;
                }
            }

            array_pop($elements);
        }
    }

    private function x_calculatePermutations($length, $with_repetition = false, $elements = array(), $keys = array()): Generator
    {

        foreach($this->_elements as $key => $value){

            if (!$with_repetition){
                if (in_array($key, $keys)){
                    continue;
                }
            }

            $keys[] = $key;
            $elements[] = $value;

            if (count($elements) == $length){
                yield $elements;
            }
            else{
                foreach ($this->x_calculatePermutations($length, $with_repetition, $elements, $keys) as $value2){
                    yield $value2;
                }
            }

            array_pop($keys);
            array_pop($elements);
        }
    }

}
