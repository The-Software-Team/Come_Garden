<?php

namespace App\Services;

use App\Support\ServiceResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected function handleTransaction(callable $callback, string $message = 'Something went wrong'): ServiceResult
    {
        try {
            return DB::transaction($callback);
        } catch (\Throwable $e) {
            Log::error($e);
            return ServiceResult::failure($message);
        }
}}