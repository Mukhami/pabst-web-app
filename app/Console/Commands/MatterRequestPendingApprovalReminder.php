<?php

namespace App\Console\Commands;

use App\Models\MatterRequestApproval;
use App\Notifications\PendingMatterRequestApprovalReminder;
use Illuminate\Console\Command;

class MatterRequestPendingApprovalReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:matter-request-pending-approval-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to send out email notifications for Matter Request Approvals that are pending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        MatterRequestApproval::query()
            ->with(['user', 'matter_request'])
            ->where('status', '=', MatterRequestApproval::STATUS_PENDING)
            ->where('reminder_counter', '<', 5)
            ->whereNull('submitted_at')
            ->where('created_at', '<', now()->subHours(2))
        ->chunkById(20, function ($matterRequestApprovals){
            foreach ($matterRequestApprovals as $matterRequestApproval) {
                $user = $matterRequestApproval->user;
                $user->notify(new PendingMatterRequestApprovalReminder($matterRequestApproval));

                $current_reminder_count = $matterRequestApproval->reminder_counter;
                $matterRequestApproval->reminder_counter = $current_reminder_count + 1;

                $matterRequestApproval->save();
            }
        });
    }
}
