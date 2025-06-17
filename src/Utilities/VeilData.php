<?php

namespace Bayfront\BonesService\WebApp\Utilities;

use Bayfront\ArrayHelpers\Arr;

class VeilData
{

    private static array $data = [];

    /**
     * Set data.
     *
     * @param array $array
     * @return void
     */
    public static function set(array $array): void
    {
        self::$data = array_merge(self::$data, $array);
    }

    /**
     * Get entire data array.
     *
     * @return array
     */
    public static function getData(): array
    {
        return self::$data;
    }

    /**
     * Get a data item using "dot" notation,
     * returning an optional default value if not found.
     *
     * @param string $key (Key to return in "dot" notation)
     * @param mixed|null $default (Default value to return)
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Arr::get(self::$data, $key, $default);
    }

    /**
     * Checks if data key exists and not null using "dot" notation.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return Arr::has(self::$data, $key);
    }

    public const ACTION_CREATE = 'create';
    public const ACTION_READ = 'read';
    public const ACTION_LIST = 'list';
    public const ACTION_EDIT = 'edit';
    public const ACTION_DELETE = 'delete';

}