<?php

namespace Pasya\OneSender;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Change log:
 * - 2022/09/21 8:50
 *   Penambahan forward untuk pesan text
 */
class Validate {

    protected $validUrlPrefixes = array('http://', 'https://', 'ftp://');

    public function isValidUrl($value) {
        foreach ($this->validUrlPrefixes as $prefix) {
                if (strpos($value, $prefix) !== false) {
                    return filter_var($value, \FILTER_VALIDATE_URL) !== false;
                }
        }

        return false;
    }
}
