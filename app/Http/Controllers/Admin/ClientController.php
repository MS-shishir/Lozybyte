<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Requests\Admin\ClientRequest;
use App\Traits\UploadsImage;

class ClientController extends Controller
{
    use UploadsImage;

    public function index(Request $request) {
        $query = Client::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }
        $clients = $query->orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.clients.index', compact('clients'));
    }

    public function create() {
        return view('admin.clients.create');
    }

    public function store(ClientRequest $request) {
        \Illuminate\Support\Facades\Cache::flush();
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $this->uploadImage($request->file('logo'), 'clients', 400, null); // Usually client logos don't need to be very large
        }

        Client::create([
            'name' => $request->name,
            'url' => $request->url,
            'logo_path' => $logoPath,
            'status' => $request->has('status'),
            'sort_order' => $request->sort_order ?? 0
        ]);
        return redirect()->route('admin.clients.index')->with('success', 'Client added successfully.');
    }

    public function edit(Client $client) {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client) {
        \Illuminate\Support\Facades\Cache::flush();
        if ($request->hasFile('logo')) {
            $this->deleteImage($client->logo_path);
            $client->logo_path = $this->uploadImage($request->file('logo'), 'clients', 400, null);
        }

        $client->update([
            'name' => $request->name,
            'url' => $request->url,
            'status' => $request->has('status'),
            'sort_order' => $request->sort_order ?? 0
        ]);
        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client) {
        \Illuminate\Support\Facades\Cache::flush();
        $this->deleteImage($client->logo_path);
        $client->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Client deleted.');
    }
}
