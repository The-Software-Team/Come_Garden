<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;

use App\Models\Tool;

class ToolLibraryService implements ToolLibraryServiceInterface {
    
    public function add_tool(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // Business rule
            if (Tool::where('name', $data['name'])->exists()) {
                throw new \DomainException('Tool with this name already exists');
            }

            Tool::create([
                'name' => $data['name'],
                'usage_status' => $data['usage_status'] ?? 'low',
                'maintenance_threshold_hours' => $data['maintenance_threshold_hours'] ?? 100,
            ]);
            
            ## Later we'll add a ServiceResult class
            return [
                'success' => True,
                'message' => 'Tool Added Successfully'
            ];
        }); 
    }



    public function book_tool(array $data) : array
    {
        return ['message' => "NO IMPLMENTATION YET"];
    }

    public function return_tool(array $data) : array
    {
        return ['message' => "NO IMPLMENTATION YET"];
    }

    public function reportDamage(array $data) : array
    {
        return ['message' => "NO IMPLMENTATION YET"];
    }    
}