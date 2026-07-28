<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use App\Models\Brand;
use App\Models\Guide;
use App\Models\Page;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Database\Eloquent\Builder;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $sitemap = Sitemap::create();

        $sitemap
            ->add(Brand::published()->get())
            ->writeToFile(public_path('sitemap.xml'));


        Guide::published()->each(function ($guide) use ($sitemap) {
            $sitemap->add(
                Url::create(url('/guides/'.$guide->slug))
                    ->setLastModificationDate($guide->updated_at)
            );
        });
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $sitemap
            ->add(Page::published()->get())
            ->writeToFile(public_path('sitemap.xml'));

        return 0;
    }
}
