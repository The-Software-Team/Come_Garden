<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseService
{
    protected function transaction(\Closure $callback)
    {
        try {
            return DB::transaction(fn() => $callback());
        } catch (\Throwable $e) {
            Log::error('Transaction failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    protected function success(
        array $data = [],
        string $message = 'Operation successful'
    ): array {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
            'errors' => null
        ];
    }

    protected function error(
        string $message = 'Operation failed',
        array $errors = []
    ): array {
        return [
            'success' => false,
            'data' => null,
            'message' => $message,
            'errors' => $errors
        ];
    }
}