<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Institute;
use App\Models\State;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
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
        $sitemap = SitemapGenerator::create(config('app.url'))->getSitemap();
        $institutes = Institute::all();
        $branches = Branch::all();
        $states = State::all();
        foreach ($institutes as $institute) {
            $parameters = [
                'rank' => $institute->type === 'iit' ? 'jee-advanced' : 'jee-main',
                'institutes' => [$institute->id],
            ];
            if ($institute->type !== 'iit') {
                $parameters['home-state'] = $institute->state;
            }
            $sitemap->add(
                TagsUrl::create(route('search-by-institute', $parameters))
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(TagsUrl::CHANGE_FREQUENCY_ALWAYS)
                    ->setPriority(0.5)
            );
            foreach (['1', '2', '3', '4', '5', '6'] as $round) {
                $parameters['round-display'] = $round;
                $sitemap->add(
                    TagsUrl::create(route('search-by-institute', $parameters))
                        ->setLastModificationDate(Carbon::now())
                        ->setChangeFrequency(TagsUrl::CHANGE_FREQUENCY_ALWAYS)
                        ->setPriority(0.5)
                );
            }
            $sitemap->add(
                TagsUrl::create(route('institute-trends', $parameters))
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(TagsUrl::CHANGE_FREQUENCY_ALWAYS)
                    ->setPriority(0.5)
            );
        }
        foreach ($branches as $branch) {
            $sitemap->add(
                TagsUrl::create(route('search-by-branch', [
                    'rank' => 'jee-advanced',
                    'branches' => [$branch->id],
                ]))->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(TagsUrl::CHANGE_FREQUENCY_ALWAYS)
                ->setPriority(0.5)
            );
            $sitemap->add(
                TagsUrl::create(route('branch-trends', [
                    'rank' => 'jee-advanced',
                    'branches' => [$branch->id],
                ]))->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(TagsUrl::CHANGE_FREQUENCY_ALWAYS)
                ->setPriority(0.5)
            );
            foreach ($states as $state) {
                $sitemap->add(
                    TagsUrl::create(route('search-by-branch', [
                        'rank' => 'jee-main',
                        'branches' => [$branch->id],
                        'home-state' => $state->id,
                    ]))->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(TagsUrl::CHANGE_FREQUENCY_ALWAYS)
                    ->setPriority(0.5)
                );
                $sitemap->add(
                    TagsUrl::create(route('branch-trends', [
                        'rank' => 'jee-main',
                        'branches' => [$branch->id],
                        'home-state' => $state->id,
                    ]))->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(TagsUrl::CHANGE_FREQUENCY_ALWAYS)
                    ->setPriority(0.5)
                );
            }
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
            if ($institute->type !== 'iit') {
                $parameters['home-state'] = $institute->state;
            }
            $sitemap->add(
                TagsUrl::create(route('round-trends', $parameters))
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(TagsUrl::CHANGE_FREQUENCY_ALWAYS)
                    ->setPriority(0.5)
            );
        }
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
