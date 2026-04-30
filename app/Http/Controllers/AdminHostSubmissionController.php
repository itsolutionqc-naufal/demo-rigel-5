<?php

namespace App\Http\Controllers;

use App\Models\HostSubmission;
use Illuminate\Http\Request;

class AdminHostSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $perPage = (int) $request->get('per_page', 10);
        $perPage = max(5, min(50, $perPage));

        $query = HostSubmission::query()
            ->with([
                'service:id,name,image',
                'saleTransaction:id,user_id,status,created_at,transaction_type,transaction_code,service_name',
                'saleTransaction.user:id,name,email',
            ])
            ->whereHas('saleTransaction', function ($q) {
                $q->where('transaction_type', 'host_submit');
            });

        if ($status !== 'all') {
            $query->whereHas('saleTransaction', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $submissions = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.host-submissions.index', [
            'submissions' => $submissions,
            'currentStatus' => $status,
            'perPage' => $perPage,
        ]);
    }
}

