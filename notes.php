
Schema::create('table_name', function (Blueprint $table) {

    // 1. Identity

    // 2. Ownership / Relations

    // 3. Business fields

    // 4. Timestamps
    $table->timestamps();
    $table->softDeletes();

    // 5. Indexes
});

// Controller
public function action(SomeRequest $request)
{
    $result = $this->service->action(
        $request->validated()
    );

    return response()->json($result);
};

// Service
public function action($data)
{
    return DB::transaction(function () use ($data) {

        // 1. Validate (business)
        // 2. Fetch models
        // 3. Apply logic
        // 4. Persist
        // 5. Fire events

    });
}