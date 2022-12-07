<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Institute;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
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
        $sitemap->add(TagsUrl::create(route('home'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('branch-list'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('branch-trends'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('institute-list'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('institute-trends'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('news'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('news.using-the-josaa-analysis-tool'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('round-trends'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('search-by-branch'))->setLastModificationDate(new Carbon('2022-12-04')))
            ->add(TagsUrl::create(route('search-by-institute'))->setLastModificationDate(new Carbon('2022-12-04')));
        $institutes = Institute::all();
        $branches = Branch::all();
        foreach ($institutes as $institute) {
            $parameters = [
                'rank' => $institute->type === 'iit' ? 'jee-advanced' : 'jee-main',
                'institutes' => [$institute->id],
            ];
            $sitemap->add(
                TagsUrl::create(route('institute-details', ['institute' => $institute->slug]))
                    ->setLastModificationDate(new Carbon('2022-12-04'))
            );
            $sitemap->add(
                TagsUrl::create(route('search-by-institute', $parameters))
                    ->setLastModificationDate(new Carbon('2022-12-04'))
            );
            $sitemap->add(
                TagsUrl::create(route('institute-trends', $parameters))
                    ->setLastModificationDate(new Carbon('2022-12-04'))
            );
        }
        foreach ($branches as $branch) {
            $sitemap->add(
                TagsUrl::create(route('branch-details', ['branch' => $branch->slug]))
                    ->setLastModificationDate(new Carbon('2022-12-04'))
            );
            $sitemap->add(
                TagsUrl::create(route('search-by-branch', [
                    'rank' => 'jee-advanced',
                    'branches' => [$branch->id],
                ]))
                ->setLastModificationDate(new Carbon('2022-12-04'))
            );
            $sitemap->add(
                TagsUrl::create(route('branch-trends', [
                    'rank' => 'jee-advanced',
                    'branches' => [$branch->id],
                ]))
                ->setLastModificationDate(new Carbon('2022-12-04'))
            );
            $sitemap->add(
                TagsUrl::create(route('search-by-branch', [
                    'rank' => 'jee-main',
                    'branches' => [$branch->id],
                ]))
                ->setLastModificationDate(new Carbon('2022-12-04'))
            );
            $sitemap->add(
                TagsUrl::create(route('branch-trends', [
                    'rank' => 'jee-main',
                    'branches' => [$branch->id],
                ]))
                ->setLastModificationDate(new Carbon('2022-12-04'))
            );
        }
        $entries = DB::table('institute_course_program')->get();
        foreach ($entries as $entry) {
            $institute = Institute::find($entry->institute_id);
            $parameters = [
                'rank' => $institute->type === 'iit' ? 'jee-advanced' : 'jee-main',
                'institute' => $institute->id,
                'course' => $entry->course_id,
                'program' => $entry->program_id,
            ];
            $sitemap->add(
                TagsUrl::create(route('round-trends', $parameters))
                    ->setLastModificationDate(new Carbon('2022-12-04'))
            );
        }
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
