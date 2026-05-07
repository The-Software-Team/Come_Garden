<?php

namespace App\Contracts\ToolLibrary;

use App\Support\ServiceResult;

interface ToolLibraryServiceInterface
{
    public function add_tool(array $data) : ServiceResult;

    public function book_tool(array $data) : ServiceResult;

    public function return_tool(array $data) : ServiceResult;

    public function reportDamage(array $data) : ServiceResult;

    public function processWaitlist(int $toolId) : ServiceResult;

    public function processScan(string $token) : ServiceResult;

    public function maintainTool(int $toolId) : void;


}