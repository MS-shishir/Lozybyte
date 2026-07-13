<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NavItem;

class NavController extends Controller
{
    public function index(Request $request)
    {
        $query = NavItem::with('parent')->orderBy('order', 'asc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }

        $navItems = $query->paginate(15);
        $parentItems = NavItem::whereNull('parent_id')->get();

        return view('admin.navbar.index', compact('navItems', 'parentItems'));
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $request->validate([
            'url' => 'required|string',
            'order' => 'required|integer',
            'label_en' => 'required|string'
        ]);

        NavItem::create([
            'parent_id' => $request->input('parent_id'),
            'label' => [
                'en' => $request->input('label_en'),
                'bn' => $request->input('label_bn') ?: $request->input('label_en')],
            'url' => $request->input('url'),
            'order' => $request->input('order', 0),
            'is_external' => $request->has('is_external'),
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.navbar.index')->with('success', 'Navigation item created successfully.');
    }

    public function update(Request $request, NavItem $navItem)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $request->validate([
            'url' => 'required|string',
            'order' => 'required|integer',
            'label_en' => 'required|string'
        ]);

        $navItem->update([
            'parent_id' => $request->input('parent_id'),
            'label' => [
                'en' => $request->input('label_en'),
                'bn' => $request->input('label_bn') ?: $request->input('label_en')],
            'url' => $request->input('url'),
            'order' => $request->input('order', 0),
            'is_external' => $request->has('is_external'),
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.navbar.index')->with('success', 'Navigation item updated successfully.');
    }

    public function destroy(NavItem $navItem)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $navItem->delete();
        return redirect()->route('admin.navbar.index')->with('success', 'Navigation item deleted successfully.');
    }
}
