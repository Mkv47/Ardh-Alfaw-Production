<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use Illuminate\Http\Request;

class TenderController extends Controller
{
    public function index()
    {
        return view('admin.tenders.index', ['items' => Tender::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.tenders.form', ['item' => new Tender]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:300',
            'description' => 'required|string',
            'type'        => 'required|string|max:100',
            'status'      => 'required|in:open,closed',
            'deadline'    => 'required|date',
            'sort_order'  => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        Tender::create($data);
        return redirect()->route('admin.tenders.index')->with('success', 'تمت إضافة المناقصة بنجاح');
    }

    public function edit(Tender $tender)
    {
        return view('admin.tenders.form', ['item' => $tender]);
    }

    public function update(Request $request, Tender $tender)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:300',
            'description' => 'required|string',
            'type'        => 'required|string|max:100',
            'status'      => 'required|in:open,closed',
            'deadline'    => 'required|date',
            'sort_order'  => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $tender->update($data);
        return redirect()->route('admin.tenders.index')->with('success', 'تم تعديل المناقصة بنجاح');
    }

    public function destroy(Tender $tender)
    {
        $tender->delete();
        return back()->with('success', 'تم حذف المناقصة');
    }
}
