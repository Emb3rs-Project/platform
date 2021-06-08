<?php

namespace App\Console\Commands;

use App\HasEmbersPermissions;
use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncPermissions extends Command
{
    use HasEmbersPermissions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions from Actions to the Database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $namespaces = $this->getPermissionNamespaces();

        DB::transaction(function () use ($namespaces) {
            foreach ($namespaces as $namespace) {
                Permission::firstOrCreate([
                    'action' => $namespace,
                    'friendly_name' => app($namespace)->getFriendlyActionName()
                ]);
            }
        });

        return 0;
    }
}
