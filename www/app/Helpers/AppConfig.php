<?php
namespace App\Helpers;

class AppConfig
{
    protected static string $path;

    public static function getPath(): string
    {
        return storage_path('app/app_config.json');
    }

    public static function all(): array
    {
        if (!file_exists(static::getPath())) return [];
        return json_decode(file_get_contents(static::getPath()), true) ?? [];
    }

    public static function get(string $key, $default = null)
    {
        return static::all()[$key] ?? $default;
    }

    public static function set(array $data): void
    {
        $current = static::all();
        file_put_contents(static::getPath(), json_encode(array_merge($current, $data), JSON_PRETTY_PRINT));
    }
}