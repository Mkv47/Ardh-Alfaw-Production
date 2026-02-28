<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services.index', ['items' => Service::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.services.form', ['item' => new Service]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'icon'        => 'required|string|max:100',
            'sort_order'  => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        Service::create($data);
        return redirect()->route('admin.services.index')->with('success', 'تمت إضافة الخدمة بنجاح');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', ['item' => $service]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'icon'        => 'required|string|max:100',
            'sort_order'  => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $service->update($data);
        return redirect()->route('admin.services.index')->with('success', 'تم تعديل الخدمة بنجاح');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'تم حذف الخدمة');
    }
}
