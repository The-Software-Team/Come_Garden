<?php

namespace App\Services;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;

use App\Models\Tool;

class ToolLibraryService extends BaseService implements ToolLibraryServiceInterface {

    public function add_tool(array $data) : array
    {
        ## Check user permissions (admin only can add_tool)
        if (!auth()->user()?->isAdmin()) {
            abort(403, 'Only admins can add tools.');
        }

        ## Check if tool Already exsit (business rules)
        if (Tool::where('name', $data['name'])->exists()) {
            return $this->error('Tool with this name already exists'); 
        }

        Tool::create([
            'name' => $data['name'],
            'usage_status' => $data['usage_status'] ?? 'low',
            'maintenance_threshold_hours' => $data['maintenance_threshold_hours'] ?? 100,
        ]);

        return $this->success([], 'Tool added successfully');
    }

    public function book_tool(array $data) : array
    {
        pass;
    }

    public function return_tool(array $data) : array
    {
        pass;
    }

    public function reportDamage(array $data) : array
    {
        pass;
    }    
}