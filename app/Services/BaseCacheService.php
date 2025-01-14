<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;

abstract class BaseCacheService
{
    protected const CACHE_PREFIX = 'default';
    protected const KEY_TRACKER = 'cache_keys';

    /**
     * Построить ключ кеша.
     */
    protected function buildCacheKey(string ...$segments): string
    {
        return implode(':', array_merge([static::CACHE_PREFIX], $segments));
    }

    /**
     * Отслеживать ключ кеша.
     */
    protected function trackCacheKey(string $cacheKey): void
    {
        $trackedKeys = Cache::get(static::KEY_TRACKER, []);
        if (!in_array($cacheKey, $trackedKeys)) {
            $trackedKeys[] = $cacheKey;
            Cache::forever(static::KEY_TRACKER, $trackedKeys);
        }
    }

    /**
     * Удалить кеш по префиксу.
     */
    protected function clearCacheByPrefix(string $prefix): void
    {
        $trackedKeys = Cache::get(static::KEY_TRACKER, []);
        $remainingKeys = [];

        foreach ($trackedKeys as $key) {
            if (str_starts_with($key, $prefix)) {
                Cache::forget($key);
            } else {
                $remainingKeys[] = $key;
            }
        }

        Cache::forever(static::KEY_TRACKER, $remainingKeys);
    }

    /**
     * Сохранить значение в кеш.
     */
    protected function cacheRemember(string $key, int $minutes, callable $callback)
    {
        $this->trackCacheKey($key);

        return Cache::remember($key, now()->addMinutes($minutes), $callback);
    }
}
