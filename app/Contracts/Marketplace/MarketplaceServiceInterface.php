<?php

namespace App\Contracts\Marketplace;

interface MarketplaceServiceInterface
{
    // ── Listings ─────────────────────────────────────────────
    public function createListing(array $data): array;
    public function getListings(array $filters = []): array;
    public function getListingById(int $id): array;
    public function getUserListings(int $userId): array;

    // ── Trades ───────────────────────────────────────────────
    public function createTrade(array $data): array;
    public function getUserTrades(int $userId): array;
    public function claimFlashListing(int $listingId, int $userId): array;

    // ── Karma (runs inside gift trades silently) ─────────────
    public function getUserKarma(int $userId): array;
    public function getKarmaLeaderboard(): array;

    // ── Allergen Guard (runs on listing create/update) ───────
    public function resolveAllergens(string $produceName): array;
    public function getUserAllergens(int $userId): array;
    public function updateUserAllergens(int $userId, array $allergens): array;

    // ── Q&A ──────────────────────────────────────────────────
    public function askQuestion(array $data): array;
    public function answerQuestion(array $data): array;
    public function getQuestions(array $filters = []): array;
    public function getQuestionById(int $id): array;
    public function getUserQuestions(int $userId): array;
    public function getUserAnswers(int $userId): array;
    public function getUserQualityScore(int $userId) : array;

    // ── Quality Rating ───────────────────────────────────────
    public function submitQualityRating(array $data): array;
    public function getListingRatings(int $listingId): array;

    // ── Canning Sessions ─────────────────────────────────────
    public function createCanningSession(array $data): array;
    public function joinCanningSession(int $sessionId, int $userId, array $contribution): array;
    public function getCanningSessions(array $filters = []): array;
    public function getCanningSessionById(int $id): array;
    public function getUserCanningSessions(int $userId): array;

    // ── Surplus Prediction (background alert) ────────────────
    public function predictSurplus(int $userId): array;

    // ── Admin ────────────────────────────────────────────────
    public function getAdminOverview(): array;
}