# LOC and Cyclomatic Complexity Metric (CCM)
 
## Methodology
 
**LOC:** Physical lines of code, excluding blank lines and comment-only lines.
 
**CCM (McCabe's Cyclomatic Complexity):**
```
V(G) = number of binary decision points + 1
```
Decision points: if, elseif, else if, foreach, for, while, case, catch,
ternary (?:), null-coalescing (??), match arm (each arm = 1), &&, ||
 
Risk scale:
- V(G) = 1–10 → simple, low risk
- V(G) = 11–20 → moderate risk
- V(G) = 21–50 → high risk, consider refactoring
- V(G) > 50 → untestable, must refactor
---
 
## Function 1: SeedBankService::withdraw()
 
```
LOC count (non-blank, non-comment lines):
  return $this->handleTransaction(...)    1
  $member = Member::find(...)             1
  if (!$member)                           1
    return ServiceResult::failure(...)    1
  $seedType = $data['seed_type']          1
  $quantity = $data['quantity']           1
  $wallet = ...->where('type', 'seedbank')->first()  1
  $availableCredits = $wallet->balance    1
  if ($availableCredits < $quantity)      1
    return ServiceResult::failure(...)    1
  $consumeResult = ...consumeMarketBatches(...)  1
  if ($consumeResult instanceof ServiceResult)  1
    return $consumeResult                 1
  $taken = $consumeResult['taken']        1
  $result = $consumeResult['breakdown']   1
  $avg_age = round(collect($result)->avg('age'), 1)  1
  $avg_viability = round(...)             1
  $origins = collect(...)...              1
  SeedBatch::create([...])               1
  $this->walletService->debit(...)        1
  event(new SeedWithdrawn($taken))        1
  return ServiceResult::success(...)      1
 
LOC = 22
```
 
**Decision points:**
1. `if (!$member)` → +1
2. `if ($availableCredits < $quantity)` → +1
3. `if ($consumeResult instanceof ServiceResult)` → +1
```
V(G) = 3 + 1 = 4
```
 
| Metric | Value |
|--------|-------|
| LOC | 22 |
| V(G) | 4 |
| Risk | Low |
 
---
 
## Function 2: SeedBankService::consumeMarketBatches() [private helper]
 
```
LOC:
  $batches = SeedBatch::where(...)...->get()    1
  if ($batches->isEmpty())                       1
    return ServiceResult::failure(...)           1
  $totalAvailable = $batches->sum('quantity')    1
  if ($totalAvailable < $quantity)               1
    return ServiceResult::failure(...)           1
  $taken = 0; $result = []                       1
  foreach ($batches as $batch)                   1
    if ($taken >= $quantity) break               1
    $available = $batch->quantity                1
    $take = min($available, $quantity - $taken)  1
    $batch->quantity -= $take; $batch->save()    1
    $taken += $take                              1
    $result[] = [...]                            1
  if (!$taken)                                   1
    return ServiceResult::failure(...)           1
  return ['taken' => $taken, 'breakdown' => ...]  1
 
LOC = 17
```
 
**Decision points:**
1. `if ($batches->isEmpty())` → +1
2. `if ($totalAvailable < $quantity)` → +1
3. `foreach ($batches as $batch)` → +1
4. `if ($taken >= $quantity) break` → +1
5. `if (!$taken)` → +1
```
V(G) = 5 + 1 = 6
```
 
| Metric | Value |
|--------|-------|
| LOC | 17 |
| V(G) | 6 |
| Risk | Low |
 
---
 
## Function 3: ToolLibraryService::book_tool()
 
```
LOC:
  return $this->handleTransaction(...)    1
  $member = Member::find(...)             1
  if (!$member)                           1
    return ServiceResult::failure(...)    1
  $tool = Tool::where(name)->first()      1
  if (!$tool)                             1
    return ServiceResult::failure(...)    1
  if ($tool->status == "maintenance")     1
    return ServiceResult::failure(...)    1
  $durationHours = (int)$data[...]        1
  $start = now(); $end = now()->addHours  1
  if ($this->isToolAvailable(...))        1
    $booking = Booking::create([...])     1
    $tool->update(['status' => 'in_use']) 1
    event(new ToolBooked(...))            1
    return ServiceResult::success(...)    1
  $alreadyWaitlisted = ToolWaitlist::... ->exists()  1
  if ($alreadyWaitlisted)                 1
    return ServiceResult::failure(...)    1
  $score = $this->calculatePriority(...)  1
  $waitlist = ToolWaitlist::create(...)   1
  event(new ToolWaitlisted(...))          1
  return ServiceResult::failure(...)      1
 
LOC = 22
```
 
**Decision points:**
1. `if (!$member)` → +1
2. `if (!$tool)` → +1
3. `if ($tool->status == "maintenance")` → +1
4. `if ($this->isToolAvailable(...))` → +1
5. `if ($alreadyWaitlisted)` → +1
```
V(G) = 5 + 1 = 6
```
 
| Metric | Value |
|--------|-------|
| LOC | 22 |
| V(G) | 6 |
| Risk | Low |
 
---
 
## Function 4: ToolLibraryService::return_tool()
 
```
LOC:
  return $this->handleTransaction(...)     1
  $booking = Booking::find(...)            1
  if (!$booking)                           1
    return ServiceResult::failure(...)     1
  $tool = Tool::find($booking->tool_id)   1
  if (!$tool)                              1
    return ServiceResult::failure(...)     1
  $now = now()                             1
  $late = $now->greaterThan($booking->end_time)  1
  $booking->update(['status' => $late ? 'overdue' : 'completed', ...])  1
  if (is_null($booking->cleaned_at))      1
    Penalty::create([...type=service...]) 1
  $hoursUsed = $booking->start_time->diffInHours($now)  1
  $tool->increment('total_usage_hours', $hoursUsed)  1
  $alreadyInMaintenance = ($tool->status == "maintenance")  1
  if (!$alreadyInMaintenance)             1
    $needsMaintenance = $tool->total_usage_hours >= $tool->maintenance_threshold_hours  1
    if ($needsMaintenance)               1
      $tool->status = 'maintenance'      1
    else                                 1
      $tool->status = 'available'        1
    $tool->save()                        1
  event(new ToolReturned($tool, $booking))  1
  return ServiceResult::success(...)     1
 
LOC = 24
```
 
**Decision points:**
1. `if (!$booking)` → +1
2. `if (!$tool)` → +1
3. `$late = $now->greaterThan(...)` (ternary) → +1
4. `if (is_null($booking->cleaned_at))` → +1
5. `if (!$alreadyInMaintenance)` → +1
6. `if ($needsMaintenance)` → +1
```
V(G) = 6 + 1 = 7
```
 
| Metric | Value |
|--------|-------|
| LOC | 24 |
| V(G) | 7 |
| Risk | Low |
 
---
 
## Function 5: RentalService::rentPlot()
 
```
LOC:
  return DB::transaction(...)              1
  $plot = Plot::with(...)->findOrFail(...)  1
  $rental = $plot->rentals()->where(...)->first()  1
  if (!$rental)                            1
    $rental = $plot->rentals()->create(...)  1
  $currentShare = $rental->participants()->sum('share')  1
  $remainingShare = 1.0 - $currentShare   1
  if ($remainingShare <= 0)               1
    return [...]                           1
  $applications = RentalApplication::where(...)->get()  1
  $approved=0; $waitlisted=0; $rejected=0  1
  foreach ($applications as $app)         1
    if ($remainingShare <= 0) break        1
    $member = $app->member                 1
    $wallet = $member->getWallet('main')   1
    if ($app->share > $remainingShare)     1
      $app->update(['status'=>'waitlisted'])  1
      $waitlisted++; continue              1
    $alreadyParticipant = ...->exists()    1
    if ($alreadyParticipant)              1
      $app->update(['status'=>'rejected']) 1
      $rejected++; continue               1
    $cost = $this->calculateRent(...)      1
    if ($wallet->balance < $cost)          1
      $app->update(['status'=>'rejected']) 1
      $rejected++; continue               1
    $wallet->decrement('balance', $cost)   1
    $rental->participants()->create(...)   1
    $remainingShare -= $app->share         1
    $approved++                            1
    $app->update(['status'=>'approved'])   1
  return [...]                             1
 
LOC = 31
```
 
**Decision points:**
1. `if (!$rental)` → +1
2. `if ($remainingShare <= 0)` → +1 (early return)
3. `foreach ($applications as $app)` → +1
4. `if ($remainingShare <= 0) break` (inside loop) → +1
5. `if ($app->share > $remainingShare)` → +1
6. `if ($alreadyParticipant)` → +1
7. `if ($wallet->balance < $cost)` → +1
```
V(G) = 7 + 1 = 8
```
 
| Metric | Value |
|--------|-------|
| LOC | 31 |
| V(G) | 8 |
| Risk | Low |
 
---
 
## Function 6: PlotService::updateSoilState()
 
```
LOC:
  $recentActivities = $plot->activities()->latest()->take(10)->get()  1
  $lastFertiliser = $recentActivities->firstWhere('type','fertilize')  1
  if ($lastFertiliser && ($lastFertiliser->fertilizer ?? null) === 'organic')  1
    $plot->update(['soil_quality' => 'recovering'])  1
    return 'recovering'                              1
  $recentCrops = $recentActivities->where('type','plant')->take(3)->pluck('crop')->values()  1
  if ($recentCrops->count() >= 3 && $recentCrops->unique()->count() === 1)  1
    $plot->update(['soil_quality' => 'depleted'])    1
    return 'depleted'                                1
  if ($recentCrops->count() >= 2 && $recentCrops->unique()->count() > 1)  1
    $plot->update(['soil_quality' => 'healthy'])     1
    return 'healthy'                                 1
  $plot->update(['soil_quality' => 'neutral'])       1
  return 'neutral'                                   1
 
LOC = 14
```
 
**Decision points:**
1. `if ($lastFertiliser && ...)` — compound: 2 conditions → +2
2. `($lastFertiliser->fertilizer ?? null)` null-coalescing → +1
3. `if ($recentCrops->count() >= 3 && ...)` compound → +2
4. `if ($recentCrops->count() >= 2 && ...)` compound → +2
```
V(G) = 7 + 1 = 8
```
 
| Metric | Value |
|--------|-------|
| LOC | 14 |
| V(G) | 8 |
| Risk | Low |
 
---
 
## Function 7: VolunteerService::reportIncident()
 
```
LOC:
  $severity = $data['severity'] ?? 'medium'        1
  $text = strtolower((...title...).(...description...))  1
  $criticalKw = ['broken glass', 'fire', ...]      1
  $highKw = ['slippery', 'wasp', ...]              1
  foreach ($criticalKw as $kw)                     1
    if (str_contains($text, $kw))                  1
      $severity = 'critical'; break                1
  if ($severity !== 'critical')                    1
    foreach ($highKw as $kw)                       1
      if (str_contains($text, $kw))               1
        $severity = 'high'; break                  1
  $id = DB::table('incidents')->insertGetId([...]) 1
  if ($severity === 'critical')                    1
    $this->broadcastEmergencyAlert(...)            1
  return ['success' => true, 'incident_id' => $id, 'severity' => $severity]  1
 
LOC = 15
```
 
**Decision points:**
1. `?? 'medium'` → +1
2. `foreach ($criticalKw as $kw)` → +1
3. `if (str_contains($text, $kw))` [critical loop] → +1
4. `if ($severity !== 'critical')` → +1
5. `foreach ($highKw as $kw)` → +1
6. `if (str_contains($text, $kw))` [high loop] → +1
7. `if ($severity === 'critical')` → +1
```
V(G) = 7 + 1 = 8
```
 
| Metric | Value |
|--------|-------|
| LOC | 15 |
| V(G) | 8 |
| Risk | Low |
 
---
 
## Function 8: VolunteerService::evaluateWeatherForShift()
 
```
LOC:
  $shift = DB::table('shifts')->where('id',$shiftId)->first()  1
  if (!$shift)                                   1
    return ['cancelled'=>false,'reason'=>'...']  1
  if (!in_array($simulatedWeather, self::BAD_WEATHER))  1
    return ['cancelled'=>false,...]              1
  DB::table('shifts')->where('id',$shiftId)->update(['status'=>'cancelled',...])  1
  $newStart = Carbon::parse($shift->start_date)->addDays(7)  1
  $newEnd = Carbon::parse($shift->end_date)->addDays(7)  1
  $newShiftId = DB::table('shifts')->insertGetId([...])  1
  $this->broadcastEmergencyAlert(1, 'Shift Rescheduled', ..., 'info')  1
  return ['cancelled'=>true,'new_shift_id'=>$newShiftId,'reason'=>...]  1
 
LOC = 11
```
 
**Decision points:**
1. `if (!$shift)` → +1
2. `if (!in_array($simulatedWeather, self::BAD_WEATHER))` → +1
```
V(G) = 2 + 1 = 3
```
 
| Metric | Value |
|--------|-------|
| LOC | 11 |
| V(G) | 3 |
| Risk | Low |
 
---
 
## Summary Table
 
| Function | LOC | V(G) | Risk Level |
|----------|-----|------|------------|
| SeedBankService::withdraw() | 22 | 4 | Low |
| SeedBankService::consumeMarketBatches() | 17 | 6 | Low |
| ToolLibraryService::book_tool() | 22 | 6 | Low |
| ToolLibraryService::return_tool() | 24 | 7 | Low |
| RentalService::rentPlot() | 31 | 8 | Low |
| PlotService::updateSoilState() | 14 | 8 | Low |
| VolunteerService::reportIncident() | 15 | 8 | Low |
| VolunteerService::evaluateWeatherForShift() | 11 | 3 | Low |
| **Total** | **156** | — | — |
 
All functions fall within the safe V(G) ≤ 10 range, confirming well-structured
service methods. The highest V(G) = 8 appears in `rentPlot()`, `updateSoilState()`,
and `reportIncident()` — all functions with multiple guard clauses and loops.
 
---

