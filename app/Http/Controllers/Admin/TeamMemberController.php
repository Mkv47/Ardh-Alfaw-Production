<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        return view('admin.team.index', ['items' => TeamMember::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.team.form', ['item' => new TeamMember]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'role'       => 'required|string|max:150',
            'bio'        => 'required|string',
            'icon'       => 'required|string|max:100',
            'whatsapp'   => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/team', 'public');
        }

        TeamMember::create($data);
        return redirect()->route('admin.team.index')->with('success', 'تمت إضافة عضو الفريق بنجاح');
    }

    public function edit(TeamMember $team)
    {
        return view('admin.team.form', ['item' => $team]);
    }

    public function update(Request $request, TeamMember $team)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'role'       => 'required|string|max:150',
            'bio'        => 'required|string',
            'icon'       => 'required|string|max:100',
            'whatsapp'   => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            if ($team->image) Storage::disk('public')->delete($team->image);
            $data['image'] = $request->file('image')->store('images/team', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($team->image) Storage::disk('public')->delete($team->image);
            $data['image'] = null;
        }

        $team->update($data);
        return redirect()->route('admin.team.index')->with('success', 'تم تعديل عضو الفريق بنجاح');
    }

    public function destroy(TeamMember $team)
    {
        if ($team->image) Storage::disk('public')->delete($team->image);
        $team->delete();
        return back()->with('success', 'تم حذف عضو الفريق');
    }
}
