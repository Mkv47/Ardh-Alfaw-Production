<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class MessageController extends Controller
{
    public function index()
    {
        $items = ContactMessage::latest()->get();
        // mark all as read
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        return view('admin.messages.index', compact('items'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'تم حذف الرسالة');
    }
}
