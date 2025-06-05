<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class MemberController
 *
 * @package App\Http\Controllers
 */
class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        if (!is_string($search)) {
            $search = '';
        }
        $query = Member::query();
        if ($search !== '') {
            $query->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        $members = $query->orderBy('name')->paginate(10)->appends($request->all());
        $statuses = ['active' => 'Active', 'suspended' => 'Suspended', 'inactive' => 'Inactive'];
        return view('vendor.argon.members.index', compact('members', 'statuses'));
    }

    /**
     * Show the form for creating a new member.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vendor.argon.members.create');
    }

    /**
     * Store a newly created member in storage.
     *
     * @param  MemberRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MemberRequest $request)
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->only(['name', 'email', 'status', 'membership_date', 'phone', 'address']);
        $member = Member::create($attributes);
        return redirect()->route('members.index')->with('status', 'Member created.');
    }

    /**
     * Display the specified member.
     *
     * @param  Member  $member
     * @return \Illuminate\View\View
     */
    public function show(Member $member)
    {
        $member->load('logPinjams');
        return view('vendor.argon.members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     *
     * @param  Member  $member
     * @return \Illuminate\View\View
     */
    public function edit(Member $member)
    {
        return view('vendor.argon.members.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     *
     * @param  Request  $request
     * @param  Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Member $member)
    {
        // Check if this is a status-only update
        if ($request->has('update_status_only')) {
            // Validate only the status field
            $validated = $request->validate([
                'status' => ['required', 'in:active,inactive,suspended'],
            ]);
            
            $member->update(['status' => $validated['status']]);
            return back()->with('status', 'Member status updated.');
        } else {
            // For member updates from the modal, we only update specific fields
            // We exclude name and email as they should not be editable
            $validated = $request->validate([
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'in:active,inactive,suspended'],
            ]);
            
            // Only update the fields that are editable
            $member->update($validated);
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Member updated successfully']);
            }
            
            return redirect()->route('members.index')->with('status', 'Member updated.');
        }
    }

    /**
     * Remove the specified member from storage.
     *
     * @param  Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('status', 'Member deleted.');
    }
}
