<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\StoreToolRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookToolRequest;
use App\Http\Requests\ReturnToolRequest;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;


class ToolController extends Controller
{
    public function __construct(
        private ToolLibraryServiceInterface $service
    ) {}

    public function store(StoreToolRequest $request)
    {
        $result = $this->service->add_tool($request->validated());

        if (!$result['success']) {
            return back()
                ->withErrors($result['errors'] ?? [])
                ->withInput();
        }

        return redirect()
            ->route('tools.create')
            ->with('message', 'Tool added successfully 🌿');
    }

    public function book(BookToolRequest $request)
    {
        return response()->json(
            $this->service->book($request->validated())
        );
    }

    public function return(ReturnToolRequest $request)
    {
        return response()->json(
            $this->service->return($request->validated())
        );
    }

    public function reportDamage(Request $request)
    {
        return response()->json(
            $this->service->reportDamage($request->all())
        );
    }
}
