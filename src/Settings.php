<?php

declare(strict_types = 1);

class Settings
{
    /**
     * @var array $settings
     */
    private static $settings = [];

    /**
     * @param string $fileName
     * @return bool
     * @throws Exception
     */
    public static function loadFromFile(string $fileName): bool
    {
        if (!is_readable($fileName)) {
            throw new Exception("Could not load and/or read from settings file '{$fileName}'");
        }

        $fileSettings = require $fileName;
        if (empty($fileSettings) || !is_array($fileSettings)) {
            throw new Exception("Settings file did not return a filled array of items");
        }

        self::$settings = array_merge(self::$settings, $fileSettings);

        return true;
    }

    /**
     * @param array $settings
     * @return bool
     * @throws Exception
     */
    public static function loadFromArray(array $settings): bool
    {
        if (empty($settings)) {
            throw new Exception("Settings array is not a filled array of items");
        }

        self::$settings = array_merge_recursive(self::$settings, $settings);

        return true;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $value = self::$settings;
        foreach (explode('.', $key) as $step) {
            if (empty($step)) {
                continue;
            }

            if (!isset($value[$step])) {
                return $default;
            }

            $value = $value[$step];
        }

        return $value;
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return self::$settings;
    }

    /**
     * @param string $key
     * @param $value
     */
    public static function set(string $key, $value)
    {
        $settings = &self::$settings;
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            $settings = &$settings[$key];
        }

        $settings[array_shift($keys)] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        $value = self::$settings;
        foreach (explode('.', $key) as $step) {
            if (empty($step)) {
                continue;
            }

            if (!isset($value[$step])) {
                return false;
            }

            $value = $value[$step];
        }

        return true;
    }

    public static function clear()
    {
        self::$settings = [];
    }
}
