<?php

namespace App\Console\Commands;

use App\Services\PlotGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GeneratePlots extends Command
{
    protected $signature = 'plots:generate
        {--width=100   : Allotment width in metres}
        {--height=100  : Allotment height in metres}
        {--road=2      : Road / gap width between plots in metres}
        {--fresh       : Drop all existing plots and regenerate from scratch}';

    protected $description = 'Generate allotment plots and persist them to the database (admin only).';

    public function __construct(private readonly PlotGeneratorService $generator)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $width  = (float) $this->option('width');
        $height = (float) $this->option('height');
        $road   = (float) $this->option('road');
        $fresh  = (bool)  $this->option('fresh');

        // ── Guard: warn if data already exists ────────────────────────────
        $existing = DB::table('plots')->count();

        if ($existing > 0 && !$fresh) {
            $this->warn("⚠  {$existing} plots already exist.");

            if (!$this->confirm('Add more plots on top of the existing ones?', false)) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }
        }

        if ($fresh && $existing > 0) {
            if (!$this->confirm("⚠  --fresh will DELETE all {$existing} existing plots. Continue?", false)) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }

            DB::table('plot_neighbors')->delete();
            DB::table('plots')->delete();
            $this->line('  Existing plots cleared.');
        }

        // ── Summary before running ────────────────────────────────────────
        $this->info('');
        $this->info('Generating allotment layout…');
        $this->table(
            ['Parameter', 'Value'],
            [
                ['Allotment width',  "{$width} m"],
                ['Allotment height', "{$height} m"],
                ['Road width',       "{$road} m"],
            ]
        );

        // ── Generate ──────────────────────────────────────────────────────
        $startId = DB::table('plots')->max('id') + 1 ?? 1;

        $result = $this->generator->generateAndPersist($width, $height, $road, $startId);

        // ── Report ────────────────────────────────────────────────────────
        $this->info('');
        $this->info('✅ Plot generation complete.');
        $this->table(
            ['Type', 'Count'],
            [
                ['Large plots', $result['total_large']],
                ['Small plots', $result['total_small']],
                ['Total',       $result['total']],
            ]
        );

        return self::SUCCESS;
    }
}