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
            56 => 5, 
        ];
        foreach ($sol as $key => $value) {
            $binValue = decbin($value);
            $col = ceil($key / 9);
            $row = $key % 9 === 0 ? 9 : $key % 9;
            $cel = (floor(($col - 1) / 3) * 3 )+ ceil($row / 3);
            print ($binValue);

            foreach($grid as $k => $c) {
                if ($c['column'] == $col || $c['row'] == $row || $c['cell'] == $cel ) {
                    $grid[$k]['values'] = $c['values'] - $binValue;
                }
            }
        } 

        print_r($grid);


    }

    
}