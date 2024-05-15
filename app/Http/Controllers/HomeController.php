<?php

namespace App\Http\Controllers;

use App\Models\MatterRequest;
use App\Models\MatterRequestApproval;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $users = User::query()->count();
        $matterRequests = MatterRequest::query()->count();
        $pendingMatterApprovals = MatterRequestApproval::query()
            ->where('user_id', '=', $user->id)
            ->where('status', '=', MatterRequestApproval::STATUS_PENDING)
            ->count();
        return view('backend.index', [
            'title' => 'Dashboard',
            'sub_title' => 'Welcome to your PPG Dashboard',
            'icon' => 'activity',
            'user' => $user,
            'users_count' => $users,
            'matter_requests_count' => $matterRequests,
            'pending_approvals_count' => $pendingMatterApprovals
        ]);
    }
}
