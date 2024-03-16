<?php

namespace App\Helpers\Validator\Base;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

interface BaseValidatorInterface
{
    public function validate(Request $request): self;

    public function isPassed(): bool;

    public function isFailed(): bool;

    public function getErrors(): Collection;

    public function setErrors(array $errors = []);

    public function appendError(string $errorMessage = '');

    public function appendErrors(array $errorMessages = []);

    public function setMessages(MessageBag $messages);

    public function getMessages(): MessageBag;
}