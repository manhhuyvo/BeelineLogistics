<?php
namespace App\Models\Helpers;

class ViewResponseFormat
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    private $code = 200;
    private $message = '';
    private $status = 'success';
    private $data = [];

    public function send()
    {
        return [
            'data' => collect($this->data)->toArray(),
            'status' => $this->status,
            'code' => $this->code,
            'message' => $this->message,
        ];
    }

    public function success()
    {
        $this->status = self::STATUS_SUCCESS;
        return $this;
    }

    public function error($code = 404)
    {
        $this->status = self::STATUS_ERROR;
        $this->code = $code;
        return $this;
    }

    public function message($message = '')
    {
        $this->message = $message;
        return $this;
    }

    public function data($data)
    {
        $this->data = $data;
        return $this;
    }
}