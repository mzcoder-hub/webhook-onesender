<?php

namespace Pasya\OneSender;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Change log:
 * - 2022/09/21 8:50
 *   Penambahan forward untuk pesan text
 */
class Shortcode {
    protected static $instance;

    
    public static function instance() {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public $client;

    public function __construct() {
        $this->client = new \Mustache_Engine(array('entity_flags' => ENT_QUOTES));
    }

    public function parse($message, $data) {
        return $this->client->render($message, $data);
    }

}