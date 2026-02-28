<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesCroppedImage;
use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    use SavesCroppedImage;
    public function index()
    {
        return view('admin.gallery.index', ['items' => GalleryItem::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.gallery.form', ['item' => new GalleryItem]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caption'    => 'required|string|max:200',
            'icon'       => 'required|string|max:100',
            'key'        => 'required|string|max:50|unique:gallery_items,key',
            'sort_order' => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->filled('image_cropped')) {
            $data['image'] = $this->saveCroppedImage($request->input('image_cropped'), 'images/gallery');
        }

        GalleryItem::create($data);
        return redirect()->route('admin.gallery.index')->with('success', 'تمت إضافة الصورة بنجاح');
    }

    public function edit(GalleryItem $gallery)
    {
        return view('admin.gallery.form', ['item' => $gallery]);
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $data = $request->validate([
            'caption'    => 'required|string|max:200',
            'icon'       => 'required|string|max:100',
            'key'        => 'required|string|max:50|unique:gallery_items,key,'.$gallery->id,
            'sort_order' => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->filled('image_cropped')) {
            if ($gallery->image) Storage::disk('public')->delete($gallery->image);
            $data['image'] = $this->saveCroppedImage($request->input('image_cropped'), 'images/gallery');
        } elseif ($request->boolean('remove_image')) {
            if ($gallery->image) Storage::disk('public')->delete($gallery->image);
            $data['image'] = null;
        }

        $gallery->update($data);
        return redirect()->route('admin.gallery.index')->with('success', 'تم تعديل الصورة بنجاح');
    }

    public function destroy(GalleryItem $gallery)
    {
        if ($gallery->image) Storage::disk('public')->delete($gallery->image);
        $gallery->delete();
        return back()->with('success', 'تم حذف العنصر');
    }
}
