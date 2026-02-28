<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('admin.clients.index', ['items' => Client::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.clients.form', ['item' => new Client]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:200',
            'icon'       => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        Client::create($data);
        return redirect()->route('admin.clients.index')->with('success', 'تمت إضافة العميل بنجاح');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.form', ['item' => $client]);
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:200',
            'icon'       => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $client->update($data);
        return redirect()->route('admin.clients.index')->with('success', 'تم تعديل العميل بنجاح');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'تم حذف العميل');
    }
}
