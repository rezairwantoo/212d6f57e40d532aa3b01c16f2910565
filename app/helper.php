<?php 

if (!function_exists('config')) {
    function config(string $key) : array|string|null
    {
        $params = explode('.', $key);
        $file = __DIR__ . "/config/{$params[0]}.php";
        if (!file_exists($file)) return null;
        $in = include $file;
        for ($i = 1; $i < count($params); $i++) { 
            if (!isset($in[$params[$i]])) {
                throw new \Exception("The \"{$key}\" config not found!");
            }
            $in = $in[$params[$i]];
        }
        return $in;
    }
}