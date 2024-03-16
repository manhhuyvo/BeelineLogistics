<?php

namespace App\Helpers\Validator\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;

class BaseValidator
{
    protected array $errors = [];
    protected MessageBag $messages;
    protected bool $passed = false; 

    public function setPassed()
    {
        $this->passed = true;
    }

    public function isPassed(): bool
    {
        return $this->passed;
    }

    public function isFailed(): bool
    {
        return !$this->passed;
    }

    public function getErrors(): Collection
    {
        return collect($this->errors);
    }

    public function setErrors(array $errors = [])
    {
        $this->errors = $errors;
    }

    public function appendError(string $errorMessage = '')
    {
        if (!empty($errorMessage)) {
            $this->errors[] = $errorMessage;
        }
    }

    public function appendErrors(array $errorMessages = [])
    {
        if (!empty($errorMessages)) {
            $this->errors = array_merge($this->errors, $errorMessages);
        }
    }

    public function setMessages(MessageBag $messages)
    {
        $this->messages = $messages;
        $this->setErrors($messages->all());
    }

    public function getMessages(): MessageBag
    {
        return $this->messages;
    }
}