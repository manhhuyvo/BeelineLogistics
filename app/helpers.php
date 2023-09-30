<?php

if (!function_exists('generateRandomString')) {
    function generateRandomString(int $outputLength = 5)
    {
        $allChars = 'abcdefghijklmnopqrstuvxyzABCDEFGHIJKLMNOPQRSTUVXYZ1234567890';

        $output = '';

        for ($i = 0; $i < $outputLength; $i++) {
            $output .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        return $output;
    }
}