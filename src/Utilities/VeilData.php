<?php

namespace Bayfront\BonesService\WebApp\Utilities;

use Bayfront\ArrayHelpers\Arr;
use Bayfront\Sanitize\Sanitize;

class VeilData
{

    /**
     * Sanitize data if string or array.
     *
     * @param mixed $data
     * @return mixed
     */
    public static function sanitize(mixed $data): mixed
    {
        $data = Sanitize::escape($data);

        if (is_string($data)) {
            $data = str_replace('$', '&#36;', $data);
        } else if (is_array($data)) {

            $dot = Arr::dot($data);
            $return = [];

            foreach ($dot as $key => $value) {
                $return[str_replace('$', '&#36;', $key)] = str_replace('$', '&#36;', $value);
            }

            return Arr::undot($return);

        }

        return $data;

    }

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
     * @param bool $sanitize
     * @return array
     */
    public static function getData(bool $sanitize = true): array
    {
        if ($sanitize === true) {
            return self::sanitize(self::$data);
        }

        return self::$data;
    }

    /**
     * Get a data item using "dot" notation,
     * returning an optional default value if not found.
     *
     * @param string $key (Key to return in "dot" notation)
     * @param mixed|null $default (Default value to return)
     * @param bool $sanitize
     * @return mixed
     */
    public static function get(string $key, mixed $default = null, bool $sanitize = true): mixed
    {
        if ($sanitize === true) {
            return self::sanitize(Arr::get(self::$data, $key, $default));
        }
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