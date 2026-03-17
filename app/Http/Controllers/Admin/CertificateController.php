<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index()
    {
        return view('admin.certificates.index', [
            'items' => Certificate::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.certificates.form', ['item' => new Certificate]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'file'       => 'required|file|mimes:pdf|max:262144',
            'thumbnail'  => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'sort_order' => 'nullable|integer',
        ]);

        $pdfPath  = $request->file('file')->storeAs(
            'certificates', Str::uuid() . '.pdf', 'public'
        );

        $thumbPath = null;
        if ($request->hasFile('thumbnail')) {
            $t = $request->file('thumbnail');
            $thumbPath = $t->storeAs('certificates/thumbs', Str::uuid() . '.' . $t->extension(), 'public');
        }

        Certificate::create([
            'title'      => $request->input('title'),
            'file'       => $pdfPath,
            'thumbnail'  => $thumbPath,
            'sort_order' => $request->input('sort_order', 0),
        ]);

        return redirect()->route('admin.certificates.index')->with('success', 'تمت إضافة الشهادة بنجاح');
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.form', ['item' => $certificate]);
    }

    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'file'       => 'nullable|file|mimes:pdf|max:20480',
            'thumbnail'  => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'title'      => $request->input('title'),
            'sort_order' => $request->input('sort_order', 0),
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($certificate->file);
            $data['file'] = $request->file('file')->storeAs(
                'certificates', Str::uuid() . '.pdf', 'public'
            );
        }

        if ($request->hasFile('thumbnail')) {
            if ($certificate->thumbnail) Storage::disk('public')->delete($certificate->thumbnail);
            $t = $request->file('thumbnail');
            $data['thumbnail'] = $t->storeAs('certificates/thumbs', Str::uuid() . '.' . $t->extension(), 'public');
        } elseif ($request->boolean('remove_thumbnail') && $certificate->thumbnail) {
            Storage::disk('public')->delete($certificate->thumbnail);
            $data['thumbnail'] = null;
        }

        $certificate->update($data);
        return redirect()->route('admin.certificates.index')->with('success', 'تم تعديل الشهادة بنجاح');
    }

    public function destroy(Certificate $certificate)
    {
        Storage::disk('public')->delete($certificate->file);
        if ($certificate->thumbnail) Storage::disk('public')->delete($certificate->thumbnail);
        $certificate->delete();
        return back()->with('success', 'تم حذف الشهادة');
    }
}
