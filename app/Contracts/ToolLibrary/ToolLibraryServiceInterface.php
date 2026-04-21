<?php

namespace App\Contracts\ToolLibrary;

interface ToolLibraryServiceInterface
{
    public function book(array $data);

    public function return(array $data);

    public function reportDamage(array $data);
}