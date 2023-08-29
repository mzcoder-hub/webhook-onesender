<?php

namespace Pasya\OneSender\Entity;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Change log:
 * - 2022/09/21 8:50
 *   Penambahan forward untuk pesan text
 */
class InboxMessage {
    public $from;
    public $sender;
    public $phone;
    public $type;
    public $content;
    public $contentId;

    private $config;

    public function __construct( $object, $config ) {
        $type               = $object->message_type;

        $this->config         = $config;

        $this->from         = $object->chat;
        $this->sender       = $object->sender;
        $this->phone        = $object->sender_phone;
        $this->type         = $type;

        $this->content      = $this->getBody($type, $object);
        $this->contentId    = $this->getBodyId($type, $object);
    }

    private function getBody($type, $object) {
        switch($type) {
            case 'response_interactive_dev':
                return $object->message_raw->templateButtonReplyMessage->selectedDisplayText ?? '';
                break;

            case 'response_list':
                return $object->message_raw->listResponseMessage->title ?? '';
                break;
                
            case 'response_button':
                return $object->message_raw->buttonsResponseMessage->Response->SelectedDisplayText ?? '';
                break;

            default: 
                return $object->message_text;
        }
    }

    private function getBodyId($type, $object) {
        switch($type) {
            case 'response_interactive_dev':
                return $object->message_raw->templateButtonReplyMessage->selectedId ?? null;
                break;

            case 'response_list':
                return $object->message_raw->listResponseMessage->singleSelectReply->selectedRowId ?? null;
                break;
                
            case 'response_button':
                return $object->message_raw->buttonsResponseMessage->selectedButtonId ?? null;
                break;
        }

        return null;
    }

    public function setConfig($config) {
        $this->config = $config;
    }

    public function getIntent() {
        $intent = empty($this->contentId) ? $this->content : $this->contentId;

        if (!$this->config['case_sensitive']) {
            $intent = strtolower($intent);
        }

        $length = $this->config['max_trigger_length'];
        if ( $length > 0 && strlen($intent) > $length ) {
            $intent = substr($intent, 0, $length);
        }

        return $intent;
    }

    public function getReplySender() {
        return str_contains($this->from, 'g.us') ? $this->from : $this->sender;
    }

    public function isMessageKnown() {
        if (str_contains($this->from, 'broadcast')) 
            return false;
        
        return in_array($this->type, [
            'text',
            'response_interactive_dev',
            'response_list',
            'response_button',
        ]);
    }
}