<?php

namespace Hasnayeen\Themes\Commands;

use Filament\Support\Facades\FilamentAsset;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpgradeCommand extends Command
{
    protected $description = 'Upgrade themes to the latest version';

    protected $signature = 'themes:upgrade';

    public function handle(): int
    {
        foreach (FilamentAsset::getStyles(['hasnayeen/themes']) as $asset) {
            $this->copyAsset($asset->getPath(), $asset->getPublicPath());
        }

        $this->components->info('Themes package upgraded successfully!');

        $this->components->alert('Please add `Hasnayeen\Themes\Http\Middleware\SetTheme` middleware to your provider `middleware` method and if you\'re using filament multi-tenancy then instead add to `tenantMiddleware` method.');

        return static::SUCCESS;
    }

    protected function copyAsset(string $from, string $to): void
    {
        $filesystem = app(Filesystem::class);

        [$from, $to] = str_replace('/', DIRECTORY_SEPARATOR, [$from, $to]);

        $filesystem->ensureDirectoryExists(
            (string) str($to)
                ->beforeLast(DIRECTORY_SEPARATOR),
        );

        $filesystem->copy($from, $to);
    }
}
