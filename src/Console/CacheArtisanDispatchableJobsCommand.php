<?php

namespace Spatie\ArtisanDispatchable\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Spatie\ArtisanDispatchable\DiscoverArtisanJobs;

class CacheArtisanDispatchableJobsCommand extends Command
{
    protected $signature = 'artisan-dispatchable:cache-artisan-dispatchable-jobs';

    protected $description = 'Cache all auto discovered artisan dispatchable jobs';

    public function handle(Filesystem $files): void
    {
        $this->info('Caching artisan dispatchable jobs...');

        $artisanJobs = (new DiscoverArtisanJobs())->getArtisanDispatchableJobs();

        $cachePath = config('artisan-dispatchable.cache_path');

        $cacheDirectory = pathinfo($cachePath, PATHINFO_DIRNAME);

        $files->makeDirectory($cacheDirectory, 0755, true, true);

        $files->put(
            $cachePath,
            '<?php return '.var_export($artisanJobs, true).';'
        );

        $this->info('All done!');
    }
}
