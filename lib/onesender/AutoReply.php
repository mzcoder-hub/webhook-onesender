<?php

namespace Pasya\OneSender;

defined('BASEPATH') OR exit('No direct script access allowed');

use Pasya\OneSender\Entity\InboxMessage;
use Ds\Map;

/**
 * Change log:
 * - 2022/09/21 8:50
 *   Penambahan forward untuk pesan text
 */
class AutoReply {

    protected $apiUrl;
    protected $apiKey;
    protected $maxTriggerLength;
    protected $caseSensitive;

    protected $intents;
    protected $anyIntent;
    
    public function __construct($config) {
        $this->apiUrl = $config['api_url'];
        $this->apiKey = $config['api_key'];
        $this->caseSensitive = (bool) $config['case_sensitive'];
        $this->maxTriggerLength = (int) $config['max_trigger_length'];
        $this->triggerMatch = $config['trigger_match'] ?? 'exact';

        $this->intents = new Map([]);
    }
    
    

    public function onAnyMessage($callback) {
        $this->anyIntent = $callback;           
    }

    public function onMessage($intent, $callback) {
        $this->intents->put($intent, $callback);
    }
    
    public function alias($intent, $index) {
        if ($this->intents->hasKey($index)) {
            $this->intents->put(
                $intent, 
                $this->intents->get($index)
            );
        }
    }

    public function dispatch() {
        $request = $this->getRequest();
        if (!$request) {
            echo 'invalid message type';
            return;
        }

        $bot = Bot::instance()
            ->setApi($this->apiUrl, $this->apiKey)
            ->setRequest($request);
        $bot->activate();

        $intent = $request->getIntent();
        
        if (!$this->intents->hasKey($intent)) {
            if (empty($this->anyIntent)) {
                echo 'No message found';
            } else {
                echo 'Fallback to master intent';
                $callback = $this->anyIntent;
                $callback($bot, $request);
            }

            return false;
        }

        $this->dispatchIntent($bot, $request, $intent);
    }

    public function dispatchIntent($bot, $request, $intent) {
        $callback = $this->intents->get($intent);
        $callback($bot, $request);
    }

    public function getRequest() {
        $request = json_decode(file_get_contents('php://input'));
       
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON');
        }

        $cfg = [
            'case_sensitive' => false,
            'max_trigger_length' => $this->maxTriggerLength,
            'trigger_match' => $this->triggerMatch,
        ];

        $inbox = new InboxMessage($request, $cfg);

        if (!$inbox->isMessageKnown()) {
            /* throw new \Exception('Tipe pesan tidak dikenal'); */
            return false;
        }

        return $inbox;
    }

   
}