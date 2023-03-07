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
            $grid[$i] = [
                'column'    => ceil($i / 9),
                'row'       => $i % 9,
                'cell'      => 0,
                'values'    => decbin(511),
            ];
        }
        
        print_r($grid);
    }

    
}