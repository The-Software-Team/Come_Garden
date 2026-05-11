# Software Quality, LOC/CCM, and OO Metrics Report
## Community Garden Management System
 
---
 
# Software Quality Factor Pairs (Non-Independent)
 
Software Quality Factors are rarely orthogonal. Below are pairs that interact
directly in this system, with code evidence for each.
 
---
 
## Pair 1: Security ↔ Usability (CONFLICT)
 
**Relationship:** Improving one degrades the other.
 
**Evidence in system:**
 
Security requirement: Gate codes are rotated via `rotateGateCode()` in
`CommunityOpsService`. Every rotation generates a new random 6-character code,
invalidating the old one for all members. The code is hashed before storage
and only shown in plaintext once.
 
```php
// CommunityOpsService::rotateGateCode()
$newCode = strtoupper(Str::random(6));
GateCode::query()->update(['active' => false]);
GateCode::create(['code' => Hash::make($newCode), 'plain_code' => $newCode, 'active' => true]);
```
 
**Conflict:** The more frequently codes rotate (higher security), the harder it
is for members to remember them (lower usability). Members must be notified
through the emergency alert system every time a rotation happens, adding
friction.
 
Similarly, the QR token system (`qr_token = Str::uuid()`) improves security
(one-time unforgeable pickup proof) but requires members to have their phone
available at the garden gate — a usability burden.
 
**Conclusion:** Security and Usability are inversely related here. Tuning one
requires accepting a cost in the other.
 
---
 
## Pair 2: Efficiency ↔ Reliability (TRADE-OFF)
 
**Relationship:** Maximising throughput can compromise transactional safety;
adding reliability guards costs performance.
 
**Evidence in system:**
 
The `SeedBankService::withdraw()` and `RentalService::rentPlot()` both wrap
their entire logic in `DB::transaction()` with `lockForUpdate()`:
 
```php
// SeedBankService — consumeMarketBatches
$batches = SeedBatch::where('owner_type', 'market')
    ->where('seed_type', $seedType)
    ->where('quantity', '>', 0)
    ->orderBy('age', 'desc')
    ->lockForUpdate()   // <-- reliability: prevents double-spend
    ->get();
```
 
`lockForUpdate()` serialises concurrent withdrawals, preventing two members
from withdrawing the same batch simultaneously (reliability). However, it also
creates row-level locks that block other transactions and reduce throughput
(efficiency). In a high-concurrency garden market, this is a direct trade-off.
 
**Conclusion:** Reliability (correctness of concurrent operations) conflicts
with Efficiency (throughput). Both cannot be maximised simultaneously.
 
---
 
## Pair 3: Maintainability ↔ Efficiency (CONFLICT)
 
**Relationship:** Clean abstraction layers add call overhead; highly optimised
code is harder to maintain.
 
**Evidence in system:**
 
Every service extends `BaseService` and routes all DB operations through
`handleTransaction()`:
 
```php
abstract class BaseService {
    protected function handleTransaction(callable $callback, string $message): ServiceResult {
        try {
            return DB::transaction($callback);
        } catch (\Throwable $e) {
            Log::error($e);
            return ServiceResult::failure($message);
        }
    }
}
```
 
This pattern improves Maintainability (one place to change error handling,
logging, transaction management). However, it introduces an extra function call
frame, closure allocation, and try/catch overhead on every service operation —
a small but real Efficiency cost.
 
The `ServiceResult` wrapper adds another layer (object instantiation per call)
over returning raw arrays, again trading Efficiency for Maintainability and
Reliability (typed, predictable return shapes).
 
**Conclusion:** The architectural choice of Template Method + Service Layer
consciously trades Efficiency for Maintainability.
 
---
 
## Pair 4: Portability ↔ Efficiency (CONFLICT)
 
**Relationship:** Database-agnostic code is less efficient than native queries.
 
**Evidence in system:**
 
`VolunteerService` uses Laravel's `DB::table()` query builder (portable across
MySQL, PostgreSQL, SQLite) rather than raw SQL:
 
```php
$heavyCount = DB::table('assignments')
    ->where('shift_id', $shiftId)
    ->where('role', 'heavy')
    ->whereNotIn('status', ['swapped'])
    ->count();
```
 
A native MySQL `SELECT COUNT(*) ... WITH (NOLOCK)` or a stored procedure would
be faster but tied to one DBMS (low portability). The ORM/query builder
approach is portable but generates less optimal SQL than hand-tuned queries.
 
**Conclusion:** Portability and Efficiency are inversely related when the
abstraction layer (Eloquent / Query Builder) prevents the query optimiser from
using database-specific features.
 
---
 
## Pair 5: Reusability ↔ Integrity (REINFORCING — positive correlation)
 
**Relationship:** These two reinforce each other via the Interface Contract
pattern.
 
**Evidence in system:**
 
Every service is bound to an interface:
```php
interface SeedBankServiceInterface {
    public function deposit(array $data): ServiceResult;
    public function withdraw(array $data): ServiceResult;
}
```
 
The interface forces all implementations to honour the same contract (Integrity
— data enters and exits in a defined, validated shape). Because the contract is
enforced at the type level, any class implementing the interface can be reused
or swapped in without risk of breaking callers (Reusability — e.g. swapping
`SeedBankService` for a `MockSeedBankService` in tests).
 
**Conclusion:** Here Integrity and Reusability reinforce each other — the same
design decision (interface contracts) improves both simultaneously.
 
---
 
## Pair 6: Correctness ↔ Robustness (REINFORCING)
 
**Relationship:** The `ServiceResult` pattern improves both simultaneously.
 
**Evidence in system:**
 
`ServiceResult::failure()` returns a structured failure object instead of
throwing an exception or returning null:
 
```php
return ServiceResult::failure("Insufficient Seed Credits");
```
 
This is Correct (the function returns exactly what its contract says — a
ServiceResult — even in error conditions) and Robust (the caller cannot crash
by receiving null or an unexpected exception; it always receives a testable
object with `success = false`).
 
**Conclusion:** Correctness and Robustness are positively correlated here —
the same design pattern improves both.

