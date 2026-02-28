<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', ['items' => Project::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.projects.form', ['item' => new Project]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:200',
            'description'    => 'required|string',
            'icon'           => 'required|string|max:100',
            'category_key'   => 'required|string|max:50',
            'category_label' => 'required|string|max:50',
            'client'         => 'required|string|max:150',
            'year'           => 'required|string|max:4',
            'sort_order'     => 'nullable|integer',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/projects', 'public');
        }

        Project::create($data);
        return redirect()->route('admin.projects.index')->with('success', 'تمت إضافة المشروع بنجاح');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', ['item' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:200',
            'description'    => 'required|string',
            'icon'           => 'required|string|max:100',
            'category_key'   => 'required|string|max:50',
            'category_label' => 'required|string|max:50',
            'client'         => 'required|string|max:150',
            'year'           => 'required|string|max:4',
            'sort_order'     => 'nullable|integer',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            if ($project->image) Storage::disk('public')->delete($project->image);
            $data['image'] = $request->file('image')->store('images/projects', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($project->image) Storage::disk('public')->delete($project->image);
            $data['image'] = null;
        }

        $project->update($data);
        return redirect()->route('admin.projects.index')->with('success', 'تم تعديل المشروع بنجاح');
    }

    public function destroy(Project $project)
    {
        if ($project->image) Storage::disk('public')->delete($project->image);
        $project->delete();
        return back()->with('success', 'تم حذف المشروع');
    }
}
