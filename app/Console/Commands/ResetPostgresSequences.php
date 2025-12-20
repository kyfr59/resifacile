<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetPostgresSequences extends Command
{
    protected $signature = 'db:reset-autoincrement {--dry-run}';
    protected $description = 'Recalcule les auto-incréments PostgreSQL (schéma public uniquement)';

    public function handle()
    {
        if (DB::getDriverName() !== 'pgsql') {
            $this->error('Commande réservée à PostgreSQL');
            return 1;
        }

        $dryRun = $this->option('dry-run');

        $rows = DB::select("
            SELECT
                n.nspname AS schema_name,
                t.relname AS table_name,
                a.attname AS column_name,
                pg_get_serial_sequence(
                    quote_ident(n.nspname) || '.' || quote_ident(t.relname),
                    a.attname
                ) AS sequence_name
            FROM pg_class t
            JOIN pg_namespace n ON n.oid = t.relnamespace
            JOIN pg_attribute a ON a.attrelid = t.oid
            WHERE t.relkind = 'r'
              AND n.nspname = 'public'
              AND a.attnum > 0
              AND NOT a.attisdropped
              AND pg_get_serial_sequence(
                    quote_ident(n.nspname) || '.' || quote_ident(t.relname),
                    a.attname
                  ) IS NOT NULL
        ");


        foreach ($rows as $row) {
            $table = $row->table_name;
            $column = $row->column_name;
            $sequence = $row->sequence_name;

            $max = DB::table($table)->max($column) ?? 0;

            $sql = "SELECT setval('$sequence', $max, true);";

            if ($dryRun) {
                $this->line("[DRY] {$table}.{$column} → {$sequence} = {$max}");
            } else {
                if ($max > 0) {
                    DB::statement($sql);
                    $this->info("✔ {$table}.{$column} recalé à {$max}");
                }
            }
        }

        $this->info($dryRun ? 'Dry-run terminé' : 'Auto-incréments recalés avec succès');
        return 0;
    }
}