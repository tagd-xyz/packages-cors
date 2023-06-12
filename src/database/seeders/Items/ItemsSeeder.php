<?php

namespace Tagd\Core\Database\Seeders\Items;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Repositories\Items\Tagds as TagdsRepo;

class ItemsSeeder extends Seeder
{
    use UsesFactories;

    /**
     * Seed the application's database for development purposes.
     *
     * @return void
     */
    public function run(array $options = [])
    {
        extract([
            'truncate' => true,
            'total' => 10,
            'totalResales' => 6,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate();
        }

        $tagdsRepo = app()->make(TagdsRepo::class);

        $date = Carbon::today()->subMonth(1);

        $retailer = Retailer::first();
        $consumer = Consumer::first();

        // retailer sell some items
        for ($i = 0; $i < $total; $i++) {
            $date->addDays(1);
            Carbon::setTestNow($date);

            Item::factory()
                ->count(1)
                ->for($retailer)
                ->has(
                    Tagd::factory()
                        ->count(1)
                        ->active()
                        ->for($consumer),
                    'tagds'
                )
                ->create();
        }

        // consumer resales some items
        $reseller = Reseller::first();

        $consumers = collect($consumer->id);

        while ($totalResales-- > 0) {
            $newConsumer = Consumer::whereNotIn('id', $consumers)->first();
            foreach (Tagd::whereStatus(TagdStatus::ACTIVE)->get() as $tagd) {

                $listedAt = $tagd->status_at->clone()->addDays(1);
                Carbon::setTestNow($listedAt);

                $tagdReseller = $tagdsRepo->createForResale($reseller, $tagd);

                $resoldAt = $listedAt->clone()->addDays(1);
                Carbon::setTestNow($resoldAt);

                $tagdsRepo->confirm($tagdReseller, $newConsumer);
            }
            $consumers->push($newConsumer->id);
        }
    }

    /**
     * Truncate tables
     *
     * @return void
     */
    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach (
            [
                (new Tagd())->getTable(),
                (new Item())->getTable(),
            ] as $table
        ) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
