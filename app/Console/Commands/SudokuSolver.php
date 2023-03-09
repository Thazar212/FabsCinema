<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SudokuSolver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sudoku:solve';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'solve a sudoku testing';
 
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->grid = [];
        for ($i = 1; $i <= 81; $i++) {
            $row = ceil($i / 9);
            $column = $i % 9 === 0 ? 9 : $i % 9;
            $cell = (floor(($row - 1) / 3) * 3 )+ ceil($column / 3);
            $this->grid[$i] = [
                'row'       => $row,
                'column'    => $column,
                'cell'      => $cell,
                'values'    => decbin(511),
            ];
        }
        
       
        $this->sol = [ //expert 
            2  => 6, 4  => 7, 7  => 3,
            17 => 9,
            20 => 4, 21 => 1, 22 => 2, 24 => 5,   
            30 => 8, 31 => 6, 32 => 7, 
            41 => 8, 44 => 3,
            46 => 5, 50 => 1,
            63 => 9,
            65 => 3, 66 => 9, 69 => 6, 70 => 5, 72 => 4,
            73 => 8, 75 => 6, 80 => 7,
        ];
        
        $this->setLevel(1);
        $this->t = true;
        while ($this->t) {
            $this->t = false;
            $this->z = false;
            //fill only possible answers
            $this->removeSolvedFromGrid();

            if ($this->t === true) {
                continue;
            }
            
            $this->solveUniqueFromCategory('column');
            if ($this->t === true) {
                continue;
            }

            $this->solveUniqueFromCategory('row');
            if ($this->t  === true) {
                continue;
            }

            $this->solveUniqueFromCategory('cell');
            if ($this->t  === true) {
                continue;
            }

            $this->removeUniqueLineInCell('row');
            if ($this->t  === true) {
                continue;
            }
            
            $this->removeUniqueLineInCell('column');
            if ($this->t  === true) {
                continue;
            }
            
            $this->removeUniqueMultiples('row');
            if ($this->t  === true) {
                continue;
            }
            /*
            $this->removeUniqueMultiples('column');
            if ($this->t  === true) {
                continue;
            }

            $this->removeUniqueMultiples('cell');
            if ($this->t  === true) {
                continue;
            }   
            */
        }
        ksort($this->sol);
        print_r($this->sol);
        print_r($this->grid);
        print("\n");
        print ("{$this->level}\n");


    }

    private function turnOffK ($n, $k)
    {
         
        $n = bindec($n);
        // k must be greater than 0
        if ($k <= 0)
            return $n;
     
        // Do & of n with a number
        // with all set bits except
        // the k'th bit
        return decbin($n & ~(1 << ($k - 1)));
    }

    private function findPosition ($b)
    {
        $n = bindec($b);
        if (!$this->isPowerOfTwo($n))
            return -1;
    
        $i = 1;
        $pos = 1;
    
        // Iterate through bits of n
        // till we find a set bit i&n
        // will be non-zero only when
        // 'i' and 'n' have a set bit
        // at same position
        while (!($i & $n))
        {
            // Unset current bit and
            // set the next bit in 'i'
            $i = $i << 1;
    
            // increment position
            ++$pos;
        }
    
        return $pos;
    }

    private function isPowerOfTwo($n)
    {
        return $n && (!($n & ($n - 1)));
        
    }

    private function isKthBitSet($n, $k)
    {
        $d = bindec($n);
        return ($d & (1 << $k));
           
    }
    
    private function getBitsOn($b)
    {
        $bitsOnString = "";
        $bitsOnArray = [];
        for ($n = 0; $n < strlen($b); $n++) {
            if ($this->isKthBitSet($b, $n)) {
                $bitsOnString .= strval($n + 1);
                $bitsOnArray[] = strval($n + 1);
            }
        }
        return [$bitsOnString, $bitsOnArray, count($bitsOnArray)];
    }

    public function fillSolution($index, $value) {
        $this->sol[$index] = $value;
        unset($this->grid[$index]);
        $this->t = true;
    }

    public function removeSolvedFromGrid() 
    {
        foreach ($this->sol as $key => $value) {
            $row = ceil($key / 9);
            $col = $key % 9 === 0 ? 9 : $key % 9;
            $cel = (floor(($row - 1) / 3) * 3 )+ ceil($col / 3);
            
            unset($this->grid[$key]);

            foreach($this->grid as $k => $c) {
                if ($c['column'] == $col || $c['row'] == $row || $c['cell'] == $cel ) {
                    $values = $this->turnOffK($c['values'], $value);
                    $pos = $this->findPosition($values);
                    if ($pos === -1) {
                        $this->grid[$k]['values'] = $values;
                    } else {
                        $this->fillSolution($k, $pos);
                    }
                }
            }
        } 
    }

    public function solveUniqueFromCategory($cat)
    {
        $this->setLevel(2);
        $categoryData = [];
        $counts = [];
        for ($i = 1; $i <= 9; $i++) {
            for ($j = 1; $j <= 9; $j++) {
                $counts[$i][$j]  = 0;
            }
        }
        foreach($this->grid as $k => $v) {
            $categoryData[$v[$cat]][$k] = $v['values'];
            $bitsOn = $this->getBitsOn($v['values']);
            foreach ($bitsOn[1] as $bitOn) {
                $counts[$v[$cat]][$bitOn]++;
            }
        }
        foreach ($counts as $categoryIndex => $categoryValue) {
            foreach ($categoryValue as $index => $count) {
                if ($count === 1) {
                    foreach ($categoryData[$categoryIndex] as $cellNum => $cellValue) {
                        if ($this->isKthBitSet($cellValue, $index - 1)) {
                            $this->fillSolution($cellNum, $index);
                        }
                    }
                    
                }
            }
        }
    }

    public function removeUniqueLineInCell($cat)
    {
        $this->setLevel(3);
        $cells = [];
        foreach($this->grid as $k => $v) {
            $cells[$v['cell']][$k]  = $v['values'];
        }
        foreach ($cells as $cellIndex => $cellNums) {
            $cellValueCat = [];
            foreach ($cellNums as $cellNum => $cellValue) {
                $category = $this->grid[$cellNum][$cat];
                $bitsOn = $this->getBitsOn($cellValue);
                foreach ($bitsOn[1] as $bitOn) {
                    $cellValueCats[$bitOn][] = $category;
                }
            }
        }
        foreach ($cellValueCats as $k => $v) {
            $cellValueCats[$k] = array_unique($cellValueCats[$k]);
        }
        
        foreach ($cellValueCats as $value => $category) {
            if (count($category) === 1) {
                foreach($this->grid as $k => $c) {
                    if ($c['cell'] != $cellIndex && $c[$cat] == $category[0]) {
                        $values = $this->turnOffK($c['values'], $value);
                        $pos = $this->findPosition($values);
                        if ($pos === -1) {
                            $this->grid[$k]['values'] = $values;
                        } else {
                            $this->fillSolution($k, $pos);
                        }
                    }
                }
            }
        }
    }

    public function setLevel($num) 
    {
        if (!isset($this->level)) {
            $this->level = 0;
        } 
        if ($num > $this->level) {
            $this->level = $num;
        }
    }

    public function removeUniqueMultiples($cat)
    {
        $this->setLevel(4);
        $category = [];
        foreach($this->grid as $k => $v) {
            $category[$v[$cat]][$k] = $v['values'];
        }

        foreach ($category as $catIndex => $catValue) {
            $commun = [];
            foreach ($catValue as $cellIndex => $cellValue) {
                foreach ($catValue as $cellIndex2 => $cellValue2) {
                    if ($cellIndex >= $cellIndex2) {
                        continue;
                    }
                    $communIndex = "";
                    for ($n = 0; $n < strlen($cellValue); $n++) {
                        if ($this->isKthBitSet($cellValue, $n) && $this->isKthBitSet($cellValue2, $n)) {
                            $communIndex .= strval($n + 1);

                        }
                    }
                    if ($communIndex) {
                        if (!isset($commun[$communIndex])) {
                            $commun[$communIndex] = 2;
                        } else {
                            $commun[$communIndex]++;
                        }
                    }
                }    
            }
            $commun = $this->filterIndexes($commun);

            foreach ($commun as $communIndex => $communValue) {
                if (strlen($communIndex) === $communValue) {
                    $indexes = str_split($communIndex);
                    foreach ($catValue as $cellIndex => $cellValue) {
                        $allTrue = true;
                        foreach($indexes as $index) {
                            if (!$this->isKthBitSet($cellValue, $index -1)) {
                                $allTrue = false;
                            }
                        }
                        for ($n = 0; $n < strlen($cellValue); $n++) {
                            if ($this->isKthBitSet($cellValue, $n)) {
                                if ((in_array($n + 1, $indexes) && !$allTrue) || (!in_array($n + 1, $indexes) && $allTrue)) {
                                    $values = $this->turnOffK($cellValue, $n = 1);
                                    $pos = $this->findPosition($values);
                                    if ($pos === -1) {
                                        $this->grid[$cellIndex]['values'] = $values;
                                        $this->z = true;
                                    } else {
                                        $this->fillSolution($cellIndex, $pos);
                                    }
                                    print "removed Index " . strval($n + 1) . " from cell {$cellIndex} \n";
                                }
                            }
                        }
                    }
                }
                
            }
        }
    }

    public function filterIndexes($commun) 
    {
        foreach($commun as $communIndex => $communValue) {
            foreach ($commun as $communIndex2 => $communValue2) {
                $strCi = strval($communIndex);
                $strCi2 = strval($communIndex2);
                if ($strCi === $strCi2) {
                    continue;
                }

                for ($i = 0; $i < strlen($strCi2); $i++) {
                    if (strpos($strCi,$strCi2[$i]) !== FALSE) {
                        $commun[$communIndex] = 99;
                        $commun[$communIndex2] = 99;
                    }
                }
            }
        }
        foreach($commun as $communIndex => $communValue) {
            if ($communValue === 99) {
                unset($commun[$communIndex]);
            }
        }
        return $commun;
    }

}