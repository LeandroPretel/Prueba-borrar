<?php

namespace App\Http\Controllers;

use Exception;

class UtilController extends Controller
{
    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function generateUniqueLocator(int $length): string
    {
        $characters = '123456789ABCDEFHJKLMNPQRSTUVWXYZ';

        // Shufle the $characters and returns substring of specified length
        // return substr(str_shuffle($characters), 0, $length);

        $token = '';
        $max = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[self::cryptoRandSecure(0, $max)];
        }
        return $token;
    }

    /**
     * @param int $min
     * @param int $max
     * @return int
     * @throws Exception
     */
    private static function cryptoRandSecure(int $min, int $max): int
    {
        $range = $max - $min;
        if ($range < 0) {
            return $min;
        } // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(random_bytes($bytes)));
            $rnd &= $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
}
