<?php

namespace App\Console\Commands;

use App\Models\Menu;
use Illuminate\Console\Command;

class ResetDailyStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:reset-daily-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset daily stock remaining to daily stock value for all menus';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting daily stock for all menus...');
        
        $menus = Menu::all();
        $count = 0;
        
        foreach ($menus as $menu) {
            $menu->update([
                'daily_stock_remaining' => $menu->daily_stock,
                'status' => $menu->daily_stock > 0 ? 'Tersedia' : 'Habis',
            ]);
            $count++;
        }
        
        $this->info("Successfully reset daily stock for {$count} menu(s).");
        
        return Command::SUCCESS;
    }
}
