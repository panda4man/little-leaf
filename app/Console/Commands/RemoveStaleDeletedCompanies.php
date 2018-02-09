<?php

namespace App\Console\Commands;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveStaleDeletedCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:remove-deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove any companies who were deleted 30 days ago.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::now();
        $thirtyDaysPrior = $today->subDay(30);

        $companies = Company::whereDate('deleted_at', '<', $thirtyDaysPrior)->withTrashed()->get();

        foreach($companies as $company) {
            $company->forceDelete();
        }
    }
}
