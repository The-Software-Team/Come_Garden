<?php

namespace App\Contracts\Volunteer;

interface VolunteerServiceInterface
{
    // ── Existing (already in your project) ──────────────────────────────────
    public function createShift(array $data): mixed;
    public function assign(array $data): mixed;
    public function complete(array $data): mixed;
    public function requestSwap(array $data): mixed;

    // ── Function 23: Communal Task Weighting Logic ───────────────────────────
    public function calculateTaskDifficultyScore(array $data): int;

    // ── Function 24: Volunteer Shift Load-Balancer ───────────────────────────
    public function checkShiftBalance(int $shiftId, string $role): array;

    // ── Function 25: Mandatory Service Hour Tracker ──────────────────────────
    public function getServiceLedger(int $memberId): object;
    public function logServiceHours(int $memberId, int $assignmentId, float $hours, string $role = 'light'): array;

    // ── Function 26: Shift Substitution Workflow ─────────────────────────────
    public function createSwapRequest(int $requesterId, int $assignmentId, ?int $targetId, string $reason): array;
    public function respondToSwapRequest(int $swapRequestId, int $responderId, string $decision): array;

    // ── Function 27: Emergency Site-Status Broadcaster ───────────────────────
    public function broadcastEmergencyAlert(int $adminId, string $title, string $message, string $severity): array;
    public function resolveEmergencyAlert(int $alertId): array;

    // ── Function 28: Communal Fund Allocation Voting ─────────────────────────
    public function createFundProposal(array $data): array;
    public function castFundVote(int $proposalId, int $memberId, string $vote, ?string $comment): array;
    public function closeFundProposal(int $proposalId): array;

    // ── Function 29: Garden Security Access Log ──────────────────────────────
    public function logSecurityAccess(int $memberId, string $gateCode, string $action, string $gateLocation): array;
    public function getAccessLogs(array $filters = [], int $perPage = 20): mixed;

    // ── Function 30: Mentorship Pairing Algorithm ────────────────────────────
    public function pairMentor(int $menteeId): array;
    public function createMentorshipPair(int $mentorId, int $menteeId, array $sharedInterests): array;

    // ── Function 31: Incident & Hazard Reporting ─────────────────────────────
    public function reportIncident(array $data): array;
    public function updateIncidentStatus(int $incidentId, string $status, ?int $assignedTo = null, ?string $resolutionNotes = null): array;

    // ── Function 32: Weather-Driven Shift Cancellation ───────────────────────
    public function evaluateWeatherForShift(int $shiftId, string $simulatedWeather): array;
}
