<?php

namespace App\Contracts\ToolLibrary;

interface ToolLibraryServiceInterface
{
    public function add_tool(array $data) : array;

    public function book_tool(array $data) : array;

    public function return_tool(array $data) : array;

    public function reportDamage(array $data) : array;
}