<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    protected $signature = 'backup:run {--keep=14 : Number of most recent backups to retain}';

    protected $description = 'Dump the database to a timestamped, gzip-compressed .sql file and prune old backups';

    public function handle(): int
    {
        if (config('database.default') !== 'mysql') {
            $this->error('backup:run only supports the mysql connection (current default: ' . config('database.default') . ').');

            return self::FAILURE;
        }

        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        $path = $dir . '/backup-' . now()->format('Y-m-d_His') . '.sql.gz';

        $gz = gzopen($path, 'w9');

        gzwrite($gz, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n");

        foreach (DB::select('SHOW TABLES') as $row) {
            $table = array_values((array) $row)[0];
            $this->dumpTable($gz, $table);
        }

        gzwrite($gz, "SET FOREIGN_KEY_CHECKS=1;\n");
        gzclose($gz);

        $this->info('Backup written to ' . $path);

        $this->prune($dir, (int) $this->option('keep'));

        return self::SUCCESS;
    }

    /**
     * @param resource $gz
     */
    private function dumpTable($gz, string $table): void
    {
        $create = DB::selectOne("SHOW CREATE TABLE `{$table}`");
        $createSql = $create->{'Create Table'};

        gzwrite($gz, "\n--\n-- Table: {$table}\n--\n\n");
        gzwrite($gz, "DROP TABLE IF EXISTS `{$table}`;\n");
        gzwrite($gz, $createSql . ";\n\n");

        foreach (DB::table($table)->get() as $row) {
            $data = (array) $row;
            $columns = implode('`, `', array_keys($data));
            $values = implode(', ', array_map(fn ($v) => $this->quote($v), array_values($data)));

            gzwrite($gz, "INSERT INTO `{$table}` (`{$columns}`) VALUES ({$values});\n");
        }
    }

    private function quote(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return DB::connection()->getPdo()->quote((string) $value);
    }

    private function prune(string $dir, int $keep): void
    {
        $files = collect(File::files($dir))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values();

        foreach ($files->slice(max($keep, 0)) as $stale) {
            File::delete($stale->getPathname());
            $this->info('Pruned old backup: ' . $stale->getFilename());
        }
    }
}
