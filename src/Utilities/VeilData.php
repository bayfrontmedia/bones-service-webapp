<?php /** @noinspection PhpUnused */

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
     * @param bool $sanitize
     * @return array
     */
    public static function getData(bool $sanitize = true): array
    {
        if ($sanitize === true) {
            return self::sanitizeArray(self::$data);
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

    /**
     * Sanitize array.
     *
     * @param array $data
     * @return array
     */
    private static function sanitizeArray(array $data): array
    {

        $return = [];

        foreach ($data as $key => $value) {

            if (is_array($value)) {
                $return[self::sanitizeString($key)] = self::sanitizeArray($value);
            } else if (is_string($value)) {
                $return[self::sanitizeString($key)] = self::sanitizeString($value);
            } else {
                $return[self::sanitizeString($key)] = $value;
            }

        }

        return $return;

    }

    /**
     * Sanitize string.
     *
     * @param string $data
     * @return string
     */
    private static function sanitizeString(string $data): string
    {
        return str_replace('$', '&#36;', htmlentities($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
    }

    /**
     * Sanitize strings and arrays for output to a Veil template.
     *
     * @param mixed $data
     * @return mixed
     */
    public static function sanitize(mixed $data): mixed
    {

        if (is_array($data)) {
            return self::sanitizeArray($data);
        } else if (is_string($data)) {
            return self::sanitizeString($data);
        }

        return $data;

    }

}