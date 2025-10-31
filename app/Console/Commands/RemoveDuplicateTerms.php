<?php

namespace App\Console\Commands;

use App\Models\Terms;
use DB;
use Illuminate\Console\Command;

class RemoveDuplicateTerms extends Command
{
    protected $signature = 'cleanup:terms';
    protected $description = 'Remove duplicate terms (case-insensitive) from terms table.';

    public function handle()
    {
        $duplicates = Terms::select(
            DB::raw('LOWER(statements) as normalized_statement'),
            'customer_id',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('normalized_statement', 'customer_id')
            ->having('count', '>', 1)
            ->get();

        $deletedCount = 0;

        foreach ($duplicates as $dup) {
            $idsToDelete = Terms::whereRaw('LOWER(statements) = ?', [$dup->normalized_statement])
                ->where('customer_id', $dup->customer_id)
                ->orderBy('id', 'asc')
                ->pluck('id')
                ->slice(1);

            Terms::whereIn('id', $idsToDelete)->delete();
            $deletedCount += $idsToDelete->count();
        }

        $this->info("✅ Removed $deletedCount duplicate term(s).");
    }
}
