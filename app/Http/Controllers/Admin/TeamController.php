<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Http\Requests\Admin\TeamRequest;
use App\Traits\UploadsImage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    use UploadsImage;

    public function index(Request $request)
    {
        $query = Team::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }

        $team = $query->paginate(12);
        return view('admin.teams.index', compact('team'));
    }

    public function create()
    {
        return redirect()->route('admin.teams.index');
    }

    public function store(TeamRequest $request)
    {
        Cache::flush();

        // Upload profile photo
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'), 'teams', 600, 600);
        }

        // Create or link an admin user account if email is provided
        $userId = null;
        if ($request->filled('email') && $request->filled('system_role')) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password ?? str()->random(16)),
                'role'     => $request->system_role,
                'status'   => $request->has('status'),
            ]);
            $userId = $user->id;
        }

        Team::create([
            'user_id'     => $userId,
            'name'        => $request->name,
            'role'        => ['en' => $request->role_en, 'bn' => $request->role_bn],
            'image_path'  => $imagePath,
            'social_links'=> [
                'linkedin' => $request->social_linkedin,
                'github'   => $request->social_github,
                'twitter'  => $request->social_twitter,
            ],
            'status'      => $request->has('status'),
        ]);

        return redirect()->route('admin.teams.index')->with('success', 'Team member added successfully.');
    }

    public function edit(Team $team)
    {
        return redirect()->route('admin.teams.index');
    }

    public function update(TeamRequest $request, Team $team)
    {
        Cache::flush();

        // Handle profile photo upload
        if ($request->hasFile('image')) {
            $this->deleteImage($team->image_path);
            $team->image_path = $this->uploadImage($request->file('image'), 'teams', 600, 600);
        }

        // Handle admin user account
        if ($request->filled('email') && $request->filled('system_role')) {
            if ($team->user_id && $team->user) {
                // Update existing linked user
                $updateData = [
                    'name'   => $request->name,
                    'email'  => $request->email,
                    'role'   => $request->system_role,
                    'status' => $request->has('status'),
                ];
                if ($request->filled('password')) {
                    $updateData['password'] = Hash::make($request->password);
                }
                $team->user->update($updateData);
            } else {
                // Create new linked user
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password ?? str()->random(16)),
                    'role'     => $request->system_role,
                    'status'   => $request->has('status'),
                ]);
                $team->user_id = $user->id;
            }
        } elseif ($team->user_id && $team->user) {
            // If email/role removed — update status only (don't delete the user)
            $team->user->update(['status' => $request->has('status')]);
        }

        $team->update([
            'name'        => $request->name,
            'role'        => ['en' => $request->role_en, 'bn' => $request->role_bn],
            'social_links'=> [
                'linkedin' => $request->social_linkedin,
                'github'   => $request->social_github,
                'twitter'  => $request->social_twitter,
            ],
            'status' => $request->has('status'),
        ]);

        $team->save();

        return redirect()->route('admin.teams.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(Team $team)
    {
        Cache::flush();
        $this->deleteImage($team->image_path);

        // Remove linked user account (only if NOT the super_admin)
        if ($team->user && $team->user->role !== 'super_admin') {
            $team->user->delete();
        }

        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Team member removed.');
    }

    // Quick toggle status (AJAX-friendly POST route)
    public function toggleStatus(Team $team)
    {
        Cache::flush();
        $team->update(['status' => !$team->status]);
        if ($team->user) {
            $team->user->update(['status' => $team->status]);
        }
        return redirect()->route('admin.teams.index')->with('success', 'Status updated.');
    }
}
