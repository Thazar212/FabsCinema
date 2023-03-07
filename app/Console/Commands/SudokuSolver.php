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
            $column = ceil($i / 9);
            $row = $i % 9 === 0 ? 9 : $i % 9;
            $cell = (floor(($column - 1) / 3) * 3 )+ ceil($row / 3);
            $grid[$i] = [
                'column'    => $column,
                'row'       => $row,
                'cell'      => $cell,
                'values'    => decbin(511),
            ];
        }
        
        $sol = [ 
            1  => 3,
            2  => 4,
            4  => 7,
            6  => 6,
            9  => 1,
            10 => 8,
            11 => 7,
            16 => 9,
            18 => 6,
            22 => 8, 
            23 => 9,
            24 => 1,
            27 => 3,
            33 => 3,
            34 => 5,
            35 => 6,
            36 => 8,
            37 => 6,
            38 => 8,
            41 => 5,
            42 => 4,
            45 => 7,
            46 => 9,
            47 => 1,
            49 => 6,
            56 => 3,
            58 => 4,
            62 => 8,
            64 => 5,
            65 => 9,
            70 => 7,
            71 => 3,
            73 => 7,
            76 => 5,
            77 => 3,
            78 => 8,
            80 => 1,
            81 => 9,

        ];
        foreach ($sol as $key => $value) {
            $col = ceil($key / 9);
            $row = $key % 9 === 0 ? 9 : $key % 9;
            $cel = (floor(($col - 1) / 3) * 3 )+ ceil($row / 3);
            
            unset($grid[$key]);

            foreach($grid as $k => $c) {
                if ($c['column'] == $col || $c['row'] == $row || $c['cell'] == $cel ) {
                    $values = $this->turnOffK($c['values'], $value);
                    $pos = findPosition($values);
                    if ($pos === -1) {
                        $grid[$k]['values'] = $values;
                    } else {
                        print($pos);
                        print("\n");
                        print($values);
                        print("\n");
                        $grid[$k]['values'] = $values;
                    }
                }
            }
        } 

        print_r($grid);


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
        $n = bindec($b)
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
}