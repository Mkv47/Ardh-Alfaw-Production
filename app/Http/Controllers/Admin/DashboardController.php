<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ContactMessage;
use App\Models\GalleryItem;
use App\Models\News;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Tender;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'counts' => [
                'services'    => Service::count(),
                'projects'    => Project::count(),
                'news'        => News::count(),
                'team'        => TeamMember::count(),
                'tenders'     => Tender::count(),
                'clients'     => Client::count(),
                'gallery'     => GalleryItem::count(),
                'messages'    => ContactMessage::count(),
                'unread'      => ContactMessage::where('is_read', false)->count(),
            ],
        ]);
    }
}
