<?php

namespace App\Services;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;

use App\Models\Tool;

class ToolLibraryService extends BaseService implements ToolLibraryServiceInterface {

    public function add_tool(array $data): array
    {
        try {
            $this->transaction(function () use ($data) {
    
                // Authorization (throw, don't abort)
                if (!auth()->user()?->isAdmin()) {
                    throw new \Exception('Only admins can add tools.', 403);
                }
    
                // Business rule
                if (Tool::where('name', $data['name'])->exists()) {
                    throw new \Exception('Tool with this name already exists');
                }
    
                Tool::create([
                    'name' => $data['name'],
                    'usage_status' => $data['usage_status'] ?? 'low',
                    'maintenance_threshold_hours' => $data['maintenance_threshold_hours'] ?? 100,
                ]);
            });
    
            return $this->success([], 'Tool added successfully');
    
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function book_tool(array $data) : array
    {
        return $this->error("NO IMPLMENTATION YET");
    }

    public function return_tool(array $data) : array
    {
        return $this->error("NO IMPLMENTATION YET");
    }

    public function reportDamage(array $data) : array
    {
        return $this->error("NO IMPLMENTATION YET");
    }    
}