<?php

namespace Webgraphe\Phlip;

class System
{
    /** @var string|null */
    private static $basePath;

    /**
     * Returns the base path for the System class. If a path is given, it replaces the base path and returns the
     * previous one.
     *
     * @param string|null $path
     * @return string
     */
    public static function basePath(string $path = null): string
    {
        if (!static::$basePath) {
            static::$basePath = dirname(__FILE__);
        }

        if (func_num_args()) {
            $previous = static::$basePath;
            static::$basePath = realpath($path);

            return $previous;
        }

        return static::$basePath;
    }

    public static function backtrace(array $backtrace): array
    {
        static $parents = [];
        if (!isset($parents[static::class])) {
            $parents[static::class] = class_parents(static::class);
        }

        $trace = [];
        if ($basePath = static::basePath()) {
            $basePath .= DIRECTORY_SEPARATOR;
        }
        $basePathLen = strlen($basePath);
        foreach ($backtrace as $call) {
            $class = $call['class'] ?? null;
            if (static::class === $class || in_array($class, $parents[static::class])) {
                continue;
            }

            $file = $call['file'] ?? null;
            $file = $basePathLen && 0 === strpos($file, $basePath) ? substr($file, $basePathLen) : $file;
            $line = $call['line'] ?? null;
            $type = $call['type'] ?? null;
            $function = $call['function'] ?? null;

            $trace[] = ($type ? "{$class}{$type}" : '')
                . ($function ? "{$function}()" : 'PHP')
                . ($file ? " @ {$file}:{$line}" : '');
        }

        return $trace;
    }
}
