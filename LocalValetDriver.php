<?php

use Valet\Drivers\LaravelValetDriver;


class LocalValetDriver extends LaravelValetDriver
{
    /**
     * Determine if the driver serves the request.
     */
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return true;
    }

    /**
     * Determine if the incoming request is for a static file.
     */
    public function isStaticFile(string $sitePath, string $siteName, string $uri)/*: string|false */
    {
        $staticFilePath = $sitePath . $uri;

        // support index.html
        if (is_dir($staticFilePath)) {
            if (file_exists($f = str_replace('//', '/', $staticFilePath . '/index.html'))) {
                return $f;
            }
        }

        if (file_exists($staticFilePath) && !is_dir($staticFilePath)) {
            return $staticFilePath;
        }
        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     */
    public function frontControllerPath(string $sitePath, string $siteName, string $uri): string
    {
        $path = $sitePath . $uri;

        // The requested resource is a directory and contains a child ‘index.php’ file.
        if (file_exists($path . '/index.php')) {
            return $path . '/index.php';
        }

        // The requested resource is not a PHP file.
        if (file_exists($path) && isset(pathinfo($path)['extension']) && pathinfo($path)['extension'] != 'php') {
            return $path;
        }

        // The requested resource is a PHP file.
        if (file_exists($path) && isset(pathinfo($path)['extension']) && pathinfo($path)['extension'] == 'php') {
            return $path;
        }

        // 404
        return false;
    }
}