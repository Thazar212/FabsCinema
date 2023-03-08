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
        $grid = [];
        for ($i = 1; $i <= 81; $i++) {
            $row = ceil($i / 9);
            $column = $i % 9 === 0 ? 9 : $i % 9;
            $cell = (floor(($row - 1) / 3) * 3 )+ ceil($column / 3);
            $grid[$i] = [
                'row'       => $row,
                'column'    => $column,
                'cell'      => $cell,
                'values'    => decbin(511),
            ];
        }
        
        $sol1 = [ //easy
            1  => 3, 2  => 4, 4  => 7, 6  => 6, 9  => 1,
            10 => 8, 11 => 7, 16 => 9, 18 => 6,
            22 => 8, 23 => 9, 24 => 1, 27 => 3, 
            33 => 3, 34 => 5, 35 => 6, 36 => 8,
            37 => 6, 38 => 8, 41 => 5, 42 => 4, 45 => 7,
            46 => 9, 47 => 1, 49 => 6,
            56 => 3, 58 => 4, 62 => 8,
            64 => 5, 65 => 9, 70 => 7, 71 => 3,
            73 => 7, 76 => 5, 77 => 3, 78 => 8, 80 => 1, 81 => 9,
        ];

        $sol2 = [ //easy
            1  => 9, 2  => 2, 3  => 6, 5  => 4, 8  => 9,
            10 => 4, 15 => 1,
            19 => 8, 20 => 5, 23 => 2, 24 => 6, 25 => 4, 27 => 9, 
            29 => 9, 30 => 7, 33 => 4, 35 => 6, 36 => 3,
            37 => 3, 39 => 2, 43 => 1,
            46 => 5, 50 => 1, 51 => 3, 53 => 4,
            59 => 7, 60 => 9, 61 => 3, 62 => 1,
            64 => 7, 65 => 4, 71 => 5, 72 => 8,
            73 => 2, 76 => 5, 76 => 3, 77 => 8, 79 => 6,
        ];

        $sol3 = [ //easy
            3  => 2, 5  => 8, 8  => 6,
            11 => 5, 12 => 6, 13 => 9, 14 => 1, 15 => 7, 17 => 3,
            20 => 4, 23 => 5, 25 => 8, 26 => 7, 27 => 1, 
            29 => 9, 34 => 6,
            37 => 6, 38 => 7, 39 => 1, 41 => 9, 42 => 5, 43 => 2,
            50 => 2, 52 => 1,
            55 => 1, 56 => 6, 57 => 7, 59 => 3, 61 => 5, 62 => 9,
            64 => 4, 65 => 8, 68 => 7, 70 => 3,
            74 => 2, 75 => 5, 76 => 4, 77 => 6,
        ];

        $sol4 = [ //easy
            2  => 7, 3  => 9, 4  => 8, 6  => 2, 8  => 6, 9  => 3,
            10 => 6, 13 => 9, 17 => 1,
            19 => 8, 21 => 3, 23 => 7, 27 => 2, 
            29 => 9, 34 => 3, 35 => 7, 36 => 1,
            38 => 6, 39 => 8, 40 => 7, 44 => 9,
            47 => 3, 48 => 1, 50 => 2, 52 => 5, 53 => 8,
            55 => 2, 56 => 8, 57 => 6, 58 => 5, 61 => 1, 62 => 3,
            //64 => 4, 65 => 8, 68 => 7, 70 => 3,
            73 => 9, 75 => 4, 76 => 3, 79 => 8, 80 => 2, 81 => 7,
        ];

        $sol5 = [ //medium
            1  => 1, 5  => 6,
            10 => 9, 11 => 8, 16 => 6, 18 => 5,
            24 => 5, 27 => 1, 
            34 => 3, 36 => 4, 
            38 => 6, 40 => 1, 41 => 3, 43 => 9,
            47 => 4, 49 => 7, 50 => 2,
            56 => 9, 57 => 3, 59 => 7, 60 => 6, 61 => 1,
            66 => 6, 67 => 4, 68 => 8, 72 => 7,
            73 => 5, 76 => 9, 78 => 2, 79 => 4, 80 => 6,
        ];

        $sol6 = [ //medium
            2  => 8, 3  => 4, 6  => 9, 7  => 7,
            11 => 7, 13 => 8, 16 => 2, 17 => 9, 18 => 5,
            19 => 9, 20 => 1, 24 => 5, 26 => 8, 
            28 => 4, 32 => 1, 34 => 5, 
            37 => 3, 41 => 2, 42 => 8, 45 => 1,
            50 => 5, 53 => 7,
            57 => 5, 60 => 4, 63 => 6,
            66 => 9, 67 => 6, 69 => 2,
            75 => 8, 79 => 1, 81 => 9,
        ];
        
        $sol7 = [ //expert 
            8  => 5, 9  => 6,
            10 => 3, 12 => 6, 16 => 1,
            20 => 7, 21 => 9, 23 => 6, 25 => 2,  
            34 => 4, 
            38 => 1, 39 => 5, 40 => 4,
            49 => 3, 54 => 2,
            56 => 5, 57 => 8,
            69 => 5, 71 => 1,
            73 => 6, 75 => 4, 76 => 7, 78 => 9, 79 => 3,
        ];
       
        $sol8= [ //expert 
            1  => 1, 9  => 7,
            11 => 5, 12 => 4,
            20 => 3, 25 => 5, 26 => 6, 27 => 2,  
            31 => 1, 33 => 3, 
            38 => 2, 40 => 8,
            46 => 9, 48 => 5, 50 => 4, 52 => 8, 54 => 1,
            57 => 2, 58 => 3, 60 => 5,
            69 => 2, 70 => 4,
            75 => 8, 79 => 1,
        ];
       
        $sol8= [ //expert 
            1  => 3, 3  => 6, 4  => 4, 6  => 7,
            12 => 8, 16 => 9, 17 => 5,
            23 => 9,  
            28 => 5, 29 => 8, 32 => 3, 
            37 => 7, 38 => 6, 43 => 1, 45 => 5,
            52 => 8, 53 => 6,
            55 => 1, 58 => 8, 61 => 3,
            71 => 4, 72 => 8,
            74 => 7, 76 => 6,
        ];


        $sol9= [ //expert 
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
        $sol = $sol9;

        $level = 1;
        $t = true;
        while ($t) {
            $t = false;
            foreach ($sol as $key => $value) {
                $row = ceil($key / 9);
                $col = $key % 9 === 0 ? 9 : $key % 9;
                $cel = (floor(($row - 1) / 3) * 3 )+ ceil($col / 3);
                
                unset($grid[$key]);

                foreach($grid as $k => $c) {
                    if ($c['column'] == $col || $c['row'] == $row || $c['cell'] == $cel ) {
                        $values = $this->turnOffK($c['values'], $value);
                        $pos = $this->findPosition($values);
                        if ($pos === -1) {
                            $grid[$k]['values'] = $values;
                        } else {
                            $t = true;
                            $sol[$k] = $pos;
                            unset($grid[$k]);
                        }
                    }
                }
            } 
            if ($t === true) {
                continue;
            }
            if ($level < 2) {
                $level = 2;
            }
            
            $cols = [];
            $rows = [];
            $cells = [];
            $counts = [];
            for ($i = 1; $i <= 9; $i++) {
                for ($j = 1; $j <= 9; $j++) {
                    $counts['cols'][$i][$j]  = 0;
                    $counts['rows'][$i][$j]  = 0;
                    $counts['cells'][$i][$j] = 0;
                }
            }
            foreach($grid as $k => $v) {
                $cols[$v['column']][$k] = $v['values'];
                $rows[$v['row']][$k]    = $v['values'];
                $cells[$v['cell']][$k]  = $v['values'];
                for ($n = 0; $n < strlen($v['values']); $n++) {
                    if ($this->isKthBitSet($v['values'], $n)) {
                        $counts['cols'][$v['column']][$n + 1]++;
                        $counts['rows'][$v['row']][$n + 1]++;
                        $counts['cells'][$v['cell']][$n + 1]++;
                    }
                }
            }
            foreach ($counts['cols'] as $column => $columnValue) {
                foreach ($columnValue as $index => $count) {
                    if ($count === 1) {
                        foreach ($cols[$column] as $cellNum => $cellValue) {
                            if ($this->isKthBitSet($cellValue, $index - 1)) {
                                $t = true;
                                $sol[$cellNum] = $index;
                                unset($grid[$cellNum]);
                            }
                        }
                        
                    }
                }
            }
            if ($t === true) {
                continue;
            }
            foreach ($counts['rows'] as $rowIndex => $rowValue) {
                foreach ($rowValue as $index => $count) {
                    if ($count === 1) {
                        foreach ($rows[$rowIndex] as $cellNum => $cellValue) {
                            if ($this->isKthBitSet($cellValue, $index - 1)) {
                                $t = true;
                                $sol[$cellNum] = $index;
                                unset($grid[$cellNum]);
                            }
                        }
                        
                    }
                }
            }
            if ($t === true) {
                continue;
            }
            foreach ($counts['cells'] as $cellIndex => $celValue) {
                foreach ($celValue as $index => $count) {
                    if ($count === 1) {
                        foreach ($cells[$cellIndex] as $cellNum => $cellValue) {
                            if ($this->isKthBitSet($cellValue, $index - 1)) {
                                $t = true;
                                $sol[$cellNum] = $index;
                                unset($grid[$cellNum]);
                            }
                        }
                        
                    }
                }
            }
            if ($t === true) {
                continue;
            }
            if ($level < 3) {
                $level = 3;
            }
            foreach ($cells as $cellIndex => $cellNums) {
                $cellValueRows = []; 
                $cellValueColumns = []; 
                foreach ($cellNums as $cellNum => $cellValue) {
                    $row = $grid[$cellNum]['row'];
                    $column = $grid[$cellNum]['column'];
                    for ($n = 0; $n < strlen($cellValue); $n++) {
                        if ($this->isKthBitSet($cellValue, $n)) {
                            $cellValueRows[$n+1][] = $row;
                            $cellValueColumns[$n+1][] = $column;
                        }
                    }
    
                }
                for ($n = 1; $n <= 9; $n++) { 
                    if (isset($cellValueRows[$n])) {
                        $cellValueRows[$n] = array_unique($cellValueRows[$n]);
                    }
                    if (isset($cellValueColumns[$n])) {
                        $cellValueColumns[$n] = array_unique($cellValueColumns[$n]);
                    }
                }
                foreach ($cellValueRows as $value => $row) {
                    if (count($row) === 1) {
                        foreach($grid as $k => $c) {
                            if ($c['cell'] != $cellIndex && $c['row'] == $row[0]) {
                                $values = $this->turnOffK($c['values'], $value);
                                $pos = $this->findPosition($values);
                                if ($pos === -1) {
                                    $grid[$k]['values'] = $values;
                                } else {
                                    $t = true;
                                    $sol[$k] = $pos;
                                    unset($grid[$k]);
                                }
                            }
                        }
                    }
                }
                foreach ($cellValueColumns as $value => $column) {
                    if (count($column) === 1) {
                        foreach($grid as $k => $c) {
                            if ($c['cell'] != $cellIndex && $c['column'] == $column[0]) {
                                $values = $this->turnOffK($c['values'], $value);
                                $pos = $this->findPosition($values);
                                if ($pos === -1) {
                                    $grid[$k]['values'] = $values;
                                } else {
                                    $t = true;
                                    $sol[$k] = $pos;
                                    unset($grid[$k]);
                                }
                            }
                        }
                    }
                }
                
            }
            if ($t === true) {
                continue;
            }
            if ($level < 4) {
                $level = 4;
            }
            foreach ($rows as $rowIndex => $row) {
                $nbCells = count($row);
                if ($rowIndex === 3) {
                    $commun = [];
                    foreach ($row as $cellIndex => $cellValue) {
                        foreach ($row as $cellIndex2 => $cellValue2) {
                            if ($cellIndex >= $cellIndex2) {
                                continue;
                            }
                            //print("{$cellIndex} => {$cellValue} :: {$cellIndex2} => {$cellValue2}\n");
                            $communIndex = "";
                            for ($n = 0; $n < strlen($cellValue); $n++) {
                                if ($this->isKthBitSet($cellValue, $n) && $this->isKthBitSet($cellValue2, $n)) {
                                    $communIndex .= strval($n + 1);

                                }
                            }
                            
                            if ($communIndex) {
                                foreach ($commun as $ci => $cnb) {
                                    if (strval($communIndex) != strval($ci) && strpos(strval($communIndex), strval($ci)) !== false) {
                                        continue(2);
                                    }
                                    else {

                                    }
                                }
                                if (!isset($commun[$communIndex])) {
                                    $commun[$communIndex] = 2;
                                } else {
                                    $commun[$communIndex]++;
                                }
                            }
                        }    
                                         
                    }
                    foreach ($commun as $communIndex => $communValue) {
                        if (strlen($communIndex) === $communValue) {
                                $indexes = str_split($communIndex);
                                $allTrue = true;
                                print_r($indexes);
                                foreach ($row as $cellIndex => $cellValue) {
                                    foreach($indexes as $index) {
                                        if (!$this->isKthBitSet($cellValue, $index -1)) {
                                            $allTrue = false;
                                        }
                                    }
                                    print ("{$cellIndex} : {$allTrue}\n");
                                    for ($n = 0; $n < strlen($cellValue); $n++) {
                                        if ($this->isKthBitSet($cellValue, $n)) {
                                            if (($n == $index -1 && !$allTrue) || ($n != $index -1 && $allTrue)) {
                                                print "remove Index {$index} from cell {$cellIndex} \n";
                                            }
        
                                        }
                                    }
                                }

                        }
                    }
                    print("\n");
                    print("{$nbCells} \n");
                    print_r($row);
                }
            }

        }
        exit();
        ksort($sol);
        print_r($sol);
        print_r($grid);
        print("\n");
        print ("{$level}\n");


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

    private function getBitCount ($value)
    {
        $count = 0;
        while($value)
        {
            $count += ($value & 1);
            $value = $value >> 1;
        }
        return $count;
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

    private function bitAtGivenPosSetOrUnset($n, $k)
    {
        $new_num = $n >> ($k - 1);
        
        // if it results to '1' then bit is set,
        // else it results to '0' bit is unset
        return ($new_num & 1);
    }
    
    private function isKthBitSet($n, $k)
    {
        $d = bindec($n);
        return ($d & (1 << $k));
           
    }
}