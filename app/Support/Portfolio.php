<?php

namespace App\Support;

use Illuminate\Support\Collection;

class Portfolio
{
    public static function entries(string $key): Collection
    {
        return collect(config("portfolio.$key", []))
            ->filter(fn (array $entry) => ($entry['published'] ?? false) && ! ($entry['demo'] ?? false));
    }

    public static function whatsapp(?string $message = null): string
    {
        return 'https://wa.me/'.config('portfolio.whatsapp').'?text='.rawurlencode($message ?? config('portfolio.whatsapp_message'));
    }

    public static function canonical(string $path = ''): string
    {
        return rtrim(config('app.url'), '/').($path ? '/'.ltrim($path, '/') : '');
    }

    public static function indexable(): bool
    {
        return app()->environment('production') && (bool) config('portfolio.indexable');
    }
}
