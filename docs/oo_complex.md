# OO Complexity Metrics
 
## Formulas Used
 
```
WMC  = Σ complexity(method_i)  for all methods in class
       (using V(G) per method; if all V(G)=1: WMC = number of methods)
 
DIT  = depth of class in inheritance tree (root = 0)
 
NOC  = number of direct children (subclasses)
 
CBO  = number of distinct classes this class is coupled to
       (uses, instantiates, receives as parameter, calls methods on)
 
RFC  = |M| + |R|
       where M = set of methods in class,
             R = set of remote methods called by methods in this class
       RFC = number of methods in class + number of distinct external
             method calls
 
LCOM = |P| - |Q|  if |P| > |Q|, else 0
       where P = pairs of methods that share NO instance variables
             Q = pairs of methods that share AT LEAST ONE instance variable
       (Chidamber & Kemerer original definition)
       Simplified: LCOM = 1 - (sum of method-attribute uses) / (|M| × |A|)
       where |M| = method count, |A| = attribute count
       High LCOM → low cohesion (bad); LCOM = 0 → perfectly cohesive
```
 
---
 
## Class 1: BaseService
 
**Attributes:** none (stateless abstract)
**Methods:** handleTransaction() — V(G)=2 (try + catch = 2 decisions)
 
```
WMC = V(G)(handleTransaction) = 2
 
DIT = 1
  (BaseService → no explicit parent, but implicitly extends Object → depth 1)
 
NOC = 4
  (MarketPlaceService, SeedBankService, ToolLibraryService, VolunteerService)
 
CBO = 2
  (DB::transaction, Log::error — 2 distinct external classes used)
 
RFC = 1 method + 2 external calls (DB::transaction, Log::error) = 3
 
LCOM = N/A — no instance attributes; single method, so no pairs = 0
```
 
| Metric | Value |
|--------|-------|
| WMC | 2 |
| DIT | 1 |
| NOC | 4 |
| CBO | 2 |
| RFC | 3 |
| LCOM | 0 |
 
---
 
## Class 2: SeedBankService
 
**Attributes (instance):** `$walletService` (1 attribute)
**Methods:**
- deposit() → V(G)=4
- withdraw() → V(G)=4
- addInventoryItem() → V(G)=1
- checkSeedHealth() → V(G)=3
- checkInventoryAlerts() → V(G)=2
- consumeMarketBatches() → V(G)=6 [private]
- isExpired() → V(G)=1
- needsTesting() → V(G)=1
```
WMC = 4+4+1+3+2+6+1+1 = 22
 
DIT = 2
  (SeedBankService → BaseService → Object)
 
NOC = 0  (no subclasses)
 
CBO = 7 distinct external classes coupled to:
  Member, SeedBatch, InventoryItem, WalletService(interface),
  ServiceResult, DB, SeedWithdrawn(event)
 
RFC = 8 methods in class
    + external calls:
      Member::find, SeedBatch::create, SeedBatch::where,
      InventoryItem::create, InventoryItem::whereColumn,
      WalletServiceInterface::credit, WalletServiceInterface::debit,
      ServiceResult::success, ServiceResult::failure,
      DB::transaction, Log (via BaseService),
      collect(), round(), event(), Carbon
    = 8 + 15 = 23
 
LCOM: 
  |A| = 1 ($walletService)
  Methods using $walletService: deposit(), withdraw() = 2 methods
  Methods NOT using $walletService: 6 methods
  
  Using CK formula:
  LCOM = 1 - (Σ uses per attribute / |M| × |A|)
       = 1 - (2 / (8 × 1))
       = 1 - 0.25
       = 0.75  (moderate lack of cohesion — expected, as helper methods
                don't need the wallet)
```
 
| Metric | Value |
|--------|-------|
| WMC | 22 |
| DIT | 2 |
| NOC | 0 |
| CBO | 7 |
| RFC | 23 |
| LCOM | 0.75 |
 
---
 
## Class 3: ToolLibraryService
 
**Attributes (instance):** none (stateless, all data via models)
**Methods (public + private):**
- add_tool() → V(G)=3
- book_tool() → V(G)=6
- return_tool() → V(G)=7
- reportDamage() → V(G)=6
- processWaitlist() → V(G)=5
- maintainTool() → V(G)=1
- processScan() → V(G)=4
- isToolAvailable() → V(G)=3
- calculatePriority() → V(G)=4
```
WMC = 3+6+7+6+5+1+4+3+4 = 39
 
DIT = 2  (ToolLibraryService → BaseService → Object)
 
NOC = 0
 
CBO = 9 distinct external classes:
  Member, Tool, Booking, Penalty, ToolWaitlist,
  ServiceResult, ToolBooked, ToolReturned, ToolMaintained,
  ToolWaitlisted events, Str, Carbon
 
CBO = 9
 
RFC = 9 methods
    + external calls:
      Member::find, Tool::where, Tool::find, Tool::findOrFail,
      Booking::create, Booking::find, Booking::where,
      Penalty::create, Penalty::where,
      ToolWaitlist::create, ToolWaitlist::where,
      ServiceResult::success, ServiceResult::failure,
      event() × 4 (ToolBooked, ToolReturned, ToolMaintained, ToolWaitlisted),
      Str::uuid, Carbon (now), DB::transaction
    = 9 + 22 = 31
 
LCOM = 0 (no instance attributes → perfectly cohesive by definition,
           all methods operate on injected/model data)
```
 
| Metric | Value |
|--------|-------|
| WMC | 39 |
| DIT | 2 |
| NOC | 0 |
| CBO | 9 |
| RFC | 31 |
| LCOM | 0 |
 
---
 
## Class 4: MarketPlaceService
 
**Attributes (class constants, not instance):** ALLERGEN_MAP, KARMA_PER_GIFT_KG,
CREDITS_PER_ANSWER, FLASH_DEFAULT_HOURS
**Instance attributes:** none
 
**Methods (counting major public):**
- createListing() → V(G)=5
- getListings() → V(G)=4
- getListingById() → V(G)=1
- getMemberListings() → V(G)=1
- createTrade() → V(G)=4
- claimFlashListing() → V(G)=3
- getMemberTrades() → V(G)=1
- getMemberKarma() → V(G)=1
- getKarmaLeaderboard() → V(G)=1
- resolveAllergens() → V(G)=4 (foreach + foreach + if + str_contains)
- askQuestion() → V(G)=1
- answerQuestion() → V(G)=1
- getQuestions() → V(G)=3
- submitQualityRating() → V(G)=2
- createCanningSession() → V(G)=1
- joinCanningSession() → V(G)=4
- predictSurplus() → V(G)=3
- getAdminOverview() → V(G)=1
```
WMC = 5+4+1+1+4+3+1+1+1+4+1+1+3+2+1+4+3+1 = 41
 
DIT = 2  (MarketPlaceService → BaseService → Object)
 
NOC = 0
 
CBO = 12 distinct external classes:
  Listing, Trade, Question, Answer, KarmaTransaction, QualityRating,
  CanningSession, CanningContributor, Member, PlotCrop,
  ServiceResult, DB, Carbon
 
CBO = 13
 
RFC = 18 methods
    + external calls ≈ 30 distinct external method invocations
    RFC ≈ 48
 
LCOM = 0  (no instance attributes; all state flows through method parameters
            and model calls — perfectly cohesive at instance level)
```
 
| Metric | Value |
|--------|-------|
| WMC | 41 |
| DIT | 2 |
| NOC | 0 |
| CBO | 13 |
| RFC | 48 |
| LCOM | 0 |
 
---
 
## Class 5: PlotService
 
**Instance attributes:** none
**Methods:**
- updateSoilState() → V(G)=8
- plantCrop() → V(G)=1
- reportInfection() → V(G)=1
- alertNeighbors() → V(G)=2 (foreach)
- addFertilizer() → V(G)=4 (match with 4 arms)
- generateWateringSchedule() → V(G)=6
- generateWinterTasks() → V(G)=10
```
WMC = 8+1+1+2+4+6+10 = 32
 
DIT = 1  (PlotService does NOT extend BaseService; implements PlotServiceInterface)
 
NOC = 0
 
CBO = 6:
  Plot, PlotCrop, PlotInfection, Member, ServiceResult, Carbon
 
RFC = 7 methods + ~18 external calls = 25
 
LCOM = 0  (no instance attributes)
```
 
| Metric | Value |
|--------|-------|
| WMC | 32 |
| DIT | 1 |
| NOC | 0 |
| CBO | 6 |
| RFC | 25 |
| LCOM | 0 |
 
---
 
## Class 6: RentalService
 
**Instance attributes:** none
**Methods:**
- apply() → V(G)=3
- rentPlot() → V(G)=8
- endRentals() → V(G)=1
- calculateRent() [private] → V(G)=4 (match with 3 arms + 2 factors)
- getMemberTierFactor() [private] → V(G)=3 (match with 3 arms)
```
WMC = 3+8+1+4+3 = 19
 
DIT = 1  (RentalService does NOT extend BaseService)
 
NOC = 0
 
CBO = 6:
  Member, Plot, RentalApplication, Rental, Wallet, DB
 
RFC = 5 methods + ~16 external method calls = 21
 
LCOM = 0  (no instance attributes)
```
 
| Metric | Value |
|--------|-------|
| WMC | 19 |
| DIT | 1 |
| NOC | 0 |
| CBO | 6 |
| RFC | 21 |
| LCOM | 0 |
 
---
 
## Class 7: VolunteerService
 
**Instance attributes:** none
**Methods (counting all):**
- createShift() → V(G)=1
- assign() → V(G)=2
- complete() → V(G)=3
- requestSwap() → V(G)=1 (delegates)
- calculateTaskDifficultyScore() → V(G)=3
- checkShiftBalance() → V(G)=2
- getServiceLedger() → V(G)=2
- logServiceHours() → V(G)=2
- createSwapRequest() → V(G)=3
- respondToSwapRequest() → V(G)=5
- broadcastEmergencyAlert() → V(G)=1
- resolveEmergencyAlert() → V(G)=1
- createFundProposal() → V(G)=1
- castFundVote() → V(G)=4
- closeFundProposal() → V(G)=2
- logSecurityAccess() → V(G)=1
- getAccessLogs() → V(G)=5 (4 filters)
- pairMentor() → V(G)=4
- createMentorshipPair() → V(G)=1
- reportIncident() → V(G)=8
- updateIncidentStatus() → V(G)=4
- evaluateWeatherForShift() → V(G)=3
```
WMC = 1+2+3+1+3+2+2+2+3+5+1+1+1+4+2+1+5+4+1+8+4+3 = 59
 
DIT = 1  (no BaseService extension)
 
NOC = 0
 
CBO = 10 distinct external classes:
  Shift, DB, Member, ToolWaitlist(no — wrong service),
  assignments(table), swap_requests(table), emergency_alerts(table),
  fund_proposals(table), fund_votes(table), incidents(table),
  security_access_logs(table), mentorship_pairs(table),
  service_ledger(table), Carbon, Notification
 
CBO = 10 (note: uses raw DB::table, so coupling is to table names
            rather than model classes — still counted)
 
RFC = 22 methods + ~35 external calls = 57
 
LCOM = 0  (no instance state)
```
 
| Metric | Value |
|--------|-------|
| WMC | 59 |
| DIT | 1 |
| NOC | 0 |
| CBO | 10 |
| RFC | 57 |
| LCOM | 0 |
 
---
 
## Complete OO Metrics Summary Table
 
| Class | WMC | DIT | NOC | CBO | RFC | LCOM |
|-------|-----|-----|-----|-----|-----|------|
| BaseService | 2 | 1 | 4 | 2 | 3 | 0 |
| SeedBankService | 22 | 2 | 0 | 7 | 23 | 0.75 |
| ToolLibraryService | 39 | 2 | 0 | 9 | 31 | 0 |
| MarketPlaceService | 41 | 2 | 0 | 13 | 48 | 0 |
| PlotService | 32 | 1 | 0 | 6 | 25 | 0 |
| RentalService | 19 | 1 | 0 | 6 | 21 | 0 |
| VolunteerService | 59 | 1 | 0 | 10 | 57 | 0 |
 
### Interpretation
 
- **WMC:** VolunteerService (59) is the most complex class — it handles 7 features
  in one service. This is a candidate for splitting in future refactoring.
  
- **DIT:** Services extending BaseService have DIT=2, which is healthy.
  No deep inheritance chains.
  
- **NOC:** BaseService has NOC=4 (the most inherited class), confirming it is
  the correct abstraction point.
  
- **CBO:** MarketPlaceService (13) has the highest coupling — it orchestrates
  the most models. This is expected for a marketplace domain.
  
- **RFC:** VolunteerService (57) and MarketPlaceService (48) have the highest
  response sets, correlating with their large method counts and broad
  responsibilities.
  
- **LCOM:** Only SeedBankService has LCOM=0.75 (non-zero), because
  `$walletService` is only used by 2 of 8 methods. All other services
  are perfectly cohesive (LCOM=0) because they carry no instance state.
