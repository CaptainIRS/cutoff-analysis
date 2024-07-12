<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Institute;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url as TagsUrl;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = Sitemap::create();
        $sitemap->add(TagsUrl::create(route('home'))->setLastModificationDate(new Carbon('2022-12-10')))
            ->add(TagsUrl::create(route('branch-list'))->setLastModificationDate(new Carbon('2022-12-29')))
            ->add(TagsUrl::create(route('institute-list'))->setLastModificationDate(new Carbon('2022-12-29')))
            ->add(TagsUrl::create(route('news'))->setLastModificationDate(new Carbon('2022-12-10')))
            ->add(TagsUrl::create(route('news.using-the-josaa-analysis-tool'))->setLastModificationDate(new Carbon('2022-12-10')));
        $institutes = Institute::all();
        $branches = Branch::all();
        foreach ($institutes as $institute) {
            $sitemap->add(
                TagsUrl::create(route('institute-details', ['institute' => $institute->id]))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
            $sitemap->add(
                TagsUrl::create(route('search-by-institute-proxy', ['institute' => $institute->id]))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
            $sitemap->add(
                TagsUrl::create(route('institute-trends-proxy', ['institute' => $institute->id]))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
        }
        foreach ($branches as $branch) {
            $sitemap->add(
                TagsUrl::create(route('branch-details', ['branch' => $branch->id]))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
            $sitemap->add(
                TagsUrl::create(route('search-by-branch-proxy', [
                    'rank' => 'jee-advanced',
                    'branch' => $branch->id,
                ]))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
            $sitemap->add(
                TagsUrl::create(route('branch-trends-proxy', [
                    'rank' => 'jee-advanced',
                    'branch' => $branch->id,
                ]))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
            $sitemap->add(
                TagsUrl::create(route('search-by-branch-proxy', [
                    'rank' => 'jee-main',
                    'branch' => $branch->id,
                ]))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
            $sitemap->add(
                TagsUrl::create(route('branch-trends-proxy', [
                    'rank' => 'jee-main',
                    'branch' => $branch->id,
                ]))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
        }
        $entries = DB::table('institute_course_program')->get();
        foreach ($entries as $entry) {
            $institute = Institute::find($entry->institute_id);
            $parameters = [
                'institute' => $institute->id,
                'course' => $entry->course_id,
                'program' => $entry->program_id,
            ];
            $sitemap->add(
                TagsUrl::create(route('round-trends-proxy', $parameters))
                    ->setLastModificationDate(new Carbon('2022-12-29'))
            );
        }
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
