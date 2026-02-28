<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|min:2|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:30',
            'company' => 'nullable|string|max:150',
            'subject' => 'required|string|min:3|max:200',
            'message' => 'required|string|min:10',
        ]);

        ContactMessage::create($validated);

        return response()->json(['success' => true]);
    }
}
