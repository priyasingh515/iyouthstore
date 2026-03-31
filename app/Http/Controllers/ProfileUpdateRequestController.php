<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileUpdateRequest;
use App\Models\User;

class ProfileUpdateRequestController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_profile_update_request'])->only('index');
    }

    public function index()
    {
        $requests = ProfileUpdateRequest::with('user')
    ->has('user') 
    ->latest()
    ->paginate(10);
        return view('backend.profile_update.index', compact('requests'));

        return view('backend.profile_update.index', compact('requests'));
    }

    public function details($id)
    {
        $request = ProfileUpdateRequest::with('user')->findOrFail($id);

        return response()->json([
            'request' => $request
        ]);
    }

    public function store(Request $request)
    {

        $pending = ProfileUpdateRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            return back()->with('error', 'Pending request already exists');
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'new_password' => 'nullable|min:6|same:confirm_password'
        ]);

        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        if ($request->filled('phone')) {
            $data['phone'] = $request->phone;
        }

        if ($request->filled('photo')) {
            $data['avatar_original'] = $request->photo;
        }

        if ($request->filled('new_password')) {
            $data['password'] = bcrypt($request->new_password);
        }

        if (empty($data)) {
            return back()->with('error', 'Please update at least one field');
        }

        ProfileUpdateRequest::create([
            'user_id' => Auth::id(),
            'requested_data' => $data,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Profile update request sent to admin');
    }


    public function approve($id)
    {
        $requestData = ProfileUpdateRequest::findOrFail($id);

        $user = User::findOrFail($requestData->user_id);

        $allowedFields = [
            'name',
            'phone',
            'avatar_original',
            'password'
        ];

        $updateData = array_intersect_key(
            $requestData->requested_data,
            array_flip($allowedFields)
        );

        $user->update($updateData);

        $requestData->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return back()->with('success', 'Request Approved');
    }

    public function reject($id)
    {
        $requestData = ProfileUpdateRequest::findOrFail($id);

        $requestData->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return back()->with('error', 'Request Rejected');
    }

    public function delete($id)
    {
        $request = ProfileUpdateRequest::findOrFail($id);

        if ($request->status == 'pending') {
            return back()->with('error', 'Pending request cannot be deleted');
        }

        $request->delete();

        return back()->with('success', 'Request deleted successfully');
    }
}
