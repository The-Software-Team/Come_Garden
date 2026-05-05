<?php

namespace App\Support;

class ServiceResult
{
    public bool $success;
    public ?string $message;
    public array $data;

    private function __construct(bool $success, ?string $message = null, array $data = [])
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }

    public static function success(array $data = [], ?string $message = null): self
    {
        return new self(true, $message, $data);
    }

    public static function failure(string $message, array $data = []): self
    {
        return new self(false, $message, $data);
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->data,
        ];
    }
}