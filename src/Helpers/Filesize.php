<?php

namespace Motor\Backend\Helpers;

/**
 * Class Filesize
 * @package Motor\Backend\Helpers
 */
class Filesize
{

    /**
     * @param $bytes
     * @return string
     */
    public static function bytesToHuman($bytes)
    {
        $units = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB' ];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}