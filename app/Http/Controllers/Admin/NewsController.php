<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesCroppedImage;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    use SavesCroppedImage;
    public function index()
    {
        return view('admin.news.index', ['items' => News::orderBy('published_at', 'desc')->get()]);
    }

    public function create()
    {
        return view('admin.news.form', ['item' => new News]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:300',
            'excerpt'      => 'required|string',
            'icon'         => 'required|string|max:100',
            'badge'        => 'required|string|max:50',
            'category'     => 'required|string|max:100',
            'published_at' => 'required|date',
            'sort_order'   => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->filled('image_cropped')) {
            $data['image'] = $this->saveCroppedImage($request->input('image_cropped'), 'images/news');
        }

        News::create($data);
        return redirect()->route('admin.news.index')->with('success', 'تمت إضافة الخبر بنجاح');
    }

    public function edit(News $news)
    {
        return view('admin.news.form', ['item' => $news]);
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:300',
            'excerpt'      => 'required|string',
            'icon'         => 'required|string|max:100',
            'badge'        => 'required|string|max:50',
            'category'     => 'required|string|max:100',
            'published_at' => 'required|date',
            'sort_order'   => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->filled('image_cropped')) {
            if ($news->image) Storage::disk('public')->delete($news->image);
            $data['image'] = $this->saveCroppedImage($request->input('image_cropped'), 'images/news');
        } elseif ($request->boolean('remove_image')) {
            if ($news->image) Storage::disk('public')->delete($news->image);
            $data['image'] = null;
        }

        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'تم تعديل الخبر بنجاح');
    }

    public function destroy(News $news)
    {
        if ($news->image) Storage::disk('public')->delete($news->image);
        $news->delete();
        return back()->with('success', 'تم حذف الخبر');
    }
}
