<?php

function env(string $key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

function config(string $key, $default = null)
{
    global $config;

    $segments = explode('.', $key);
    $value = $config;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}
