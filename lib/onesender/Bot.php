<?php

namespace Pasya\OneSender;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Change log:
 * - 2022/09/21 8:50
 *   Penambahan forward untuk pesan text
 */

class Bot
{

    protected static $instance;

    public static function instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    protected $apiUrl;
    protected $apiKey;
    protected $request;

    protected $client;
    protected $responseMessage = [];

    public function setApi($apiUrl, $apiKey)
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
        return $this;
    }

    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function activate()
    {
        $this->client = new MessageBuilder([
            'api_url' => $this->apiUrl,
            'api_key' => $this->apiKey,
        ]);

        return $this;
    }

    public function getResponses()
    {
        return count($this->responseMessage) == 1 ? $this->responseMessage[0] : $this->responseMessage;
    }

    public function responseJson()
    {
        return json_encode($this->getResponses());
    }

    public function printResponse()
    {
        header('Content-Type: application/json');
        echo $this->responseJson();
    }

    /**
     * @return array($response, $message) */
    private function sendTextMessage($recipient = null, $message = null, $data = [])
    {
        $tparser = Shortcode::instance();
        $message = $tparser->parse($message, $data);

        return $this->client->to($recipient)
            ->content($message)
            ->send();
    }


    public function forwardCustomerNeedHelp($recipient = null, $data = [])
    {
        $messageContent = "Ada customer butuh bantuan dari nomor " . $this->request->phone;

        if (empty($messageContent)) {
            throw new \Exception('Pesan tidak valid');
        }

        list($response, $error) = $this->sendTextMessage($recipient, $messageContent, $data);

        if (empty($error)) {
            $this->responseMessage[] = $response;
        } else {
            $this->responseMessage[] = [
                'code' => 400,
                'message' => $error,
            ];
        }

        return $this;
    }

    public function forwardPesananCODBaru($recipient = null, $data = [])
    {
        $messageContent = "Ada Pesanan COD Dari " . $this->request->phone;

        if (empty($messageContent)) {
            throw new \Exception('Pesan tidak valid');
        }

        list($response, $error) = $this->sendTextMessage($recipient, $messageContent, $data);

        if (empty($error)) {
            $this->responseMessage[] = $response;
        } else {
            $this->responseMessage[] = [
                'code' => 400,
                'message' => $error,
            ];
        }

        return $this;
    }

    public function forwardKonfirmasiPembayaran($recipient = null, $data = [])
    {
        $messageContent = "Ada pembayaran baru dari " . $this->request->phone;

        if (empty($messageContent)) {
            throw new \Exception('Pesan tidak valid');
        }

        list($response, $error) = $this->sendTextMessage($recipient, $messageContent, $data);

        if (empty($error)) {
            $this->responseMessage[] = $response;
        } else {
            $this->responseMessage[] = [
                'code' => 400,
                'message' => $error,
            ];
        }

        return $this;
    }

    public function forward($recipient = null, $data = [])
    {
        $messageContent = $this->request->content;

        if (empty($messageContent)) {
            throw new \Exception('Pesan tidak valid');
        }

        list($response, $error) = $this->sendTextMessage($recipient, $messageContent, $data);

        if (empty($error)) {
            $this->responseMessage[] = $response;
        } else {
            $this->responseMessage[] = [
                'code' => 400,
                'message' => $error,
            ];
        }

        return $this;
    }

    public function reply($message = null, $data = [])
    {
        if (!$message) {
            throw new \Exception('Pesan tidak valid');
        }

        $replyTo = $this->request->getReplySender();
        list($response, $error) = $this->sendTextMessage($replyTo, $message, $data);

        if (empty($error)) {
            $this->responseMessage[] = $response;
        } else {
            $this->responseMessage[] = [
                'code' => 400,
                'message' => $error,
            ];
        }

        return $this;
    }


    public function replyImage($image = null, $message = null, $data = [])
    {
        $v = new Validate();

        if (!$v->isValidUrl($image)) {
            throw new \Exception('Link gambar tidak valid');
        }

        if (!$message) {
            throw new \Exception('Pesan tidak valid');
        }

        $tparser = Shortcode::instance();
        $message = $tparser->parse($message, $data);

        $replyTo = $this->request->getReplySender();

        list($response, $error) = $this->client->to($replyTo)
            ->type('image')
            ->attachmentUrl($image)
            ->content($message)
            ->send();

        header('Content-Type: application/json');
        if (empty($error)) {
            echo json_encode($response);
        } else {
            echo json_encode([
                'code' => 400,
                'message' => $error,
            ]);
        }
    }

    public function replyButton($message = null, $buttons = [], $data = [], $header = null, $footer = null)
    {
        $v = new Validate();

        if (!$message) {
            throw new \Exception('Pesan tidak boleh kosong');
        }

        if (!is_array($buttons)) {
            throw new \Exception('Buttons tidak valid');
        }

        if (count($buttons) == 0) {
            throw new \Exception('Buttons tidak valid');
        }

        $tparser = Shortcode::instance();
        $message = $tparser->parse($message, $data);

        $replyTo = $this->request->getReplySender();

        list($response, $error) = $this->client->to($replyTo)
            ->type('interactive_button')
            ->buttons($buttons)
            ->content($message)
            ->header($header)
            ->footer($footer)
            ->send();

        header('Content-Type: application/json');
        if (empty($error)) {
            echo json_encode($response);
        } else {
            echo json_encode([
                'code' => 400,
                'message' => $error,
            ]);
        }
    }

    public function replyList(string $message = null, string $button = '', $options = [], $data = [], $header = null, $footer = null)
    {
        $v = new Validate();

        if (!$message) {
            throw new \Exception('Pesan tidak boleh kosong');
        }

        if (empty($button)) {
            throw new \Exception('Label button tidak valid');
        }

        if (!is_array($options)) {
            throw new \Exception('Rows tidak valid');
        }

        if (count($options) == 0) {
            throw new \Exception('Rows tidak valid');
        }

        $tparser = Shortcode::instance();
        $message = $tparser->parse($message, $data);

        $replyTo = $this->request->getReplySender();

        list($response, $error) = $this->client->to($replyTo)
            ->type('interactive_list')
            ->listButton($button)
            ->listOption($options)
            ->content($message)
            ->header($header)
            ->footer($footer)
            ->send();

        header('Content-Type: application/json');
        if (empty($error)) {
            echo json_encode($response);
        } else {
            echo json_encode([
                'code' => 400,
                'message' => $error,
            ]);
        }
    }
}
