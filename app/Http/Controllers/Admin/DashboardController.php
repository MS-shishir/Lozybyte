<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Portfolio;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'total_products' => Product::count(),
            'total_portfolios' => Portfolio::count(),
            'total_posts' => Post::count(),
            'today_visitors' => \App\Models\Visit::whereDate('created_at', today())->distinct('visitor_id')->count('visitor_id'),
            'total_visits' => \App\Models\Visit::count()
        ];

        $recentLeads = Lead::latest()->take(5)->get();
        $recentLogs = \App\Models\EditLog::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentLeads', 'recentLogs'));
    }

    /**
     * Redirect to frontend with a Sanctum personal token for live visual editing.
     */
    public function liveEditor()
    {
        $user = auth()->user();
        
        // Generate a new Sanctum token for the authenticated dashboard admin
        $token = $user->createToken('admin-live-token')->plainTextToken;

        $secure = request()->isSecure() || env('APP_ENV') === 'production';
        $cookie = cookie(
            'lozybyte_admin_token',
            $token,
            120, // 2 hours
            '/',
            null, // domain
            $secure,
            true, // httpOnly
            false, // raw
            'Lax' // sameSite
        );

        // Redirect to Next.js frontend with secure params (no token in URL)
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/?edit_mode=true';

        return redirect($frontendUrl)->withCookie($cookie);
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|max:2048'
        ]);

        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        }

        if ($request->hasFile('avatar')) {
            // Delete previous avatar file if exists
            if ($user->avatar) {
                $prevPath = str_replace(asset('storage/'), '', $user->avatar);
                $prevPath = ltrim($prevPath, '/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($prevPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($prevPath);
                }
            }
            $user->avatar = \Illuminate\Support\Facades\Storage::disk('public')->url(
                $request->file('avatar')->store('avatars', 'public')
            );
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }
}
