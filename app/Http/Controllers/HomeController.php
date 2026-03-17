<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Client;
use App\Models\GalleryItem;
use App\Models\News;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Tender;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');

        return view('home', [
            'services'    => Service::orderBy('sort_order')->get(),
            'projects'    => Project::orderBy('sort_order')->get(),
            'news'        => News::orderBy('published_at', 'desc')->get(),
            'teamMembers' => TeamMember::orderBy('sort_order')->get(),
            'galleryItems'=> GalleryItem::orderBy('sort_order')->get(),
            'tenders'     => Tender::orderByRaw("status = 'open' DESC")->orderBy('sort_order')->get(),
            'clients'      => Client::orderBy('sort_order')->get(),
            'certificates' => Certificate::orderBy('sort_order')->get(),
            'sectionOrder' => json_decode(Setting::get('section_order') ?? '[]', true)
                              ?: ['services','projects','news','team','gallery','tenders','clients','certificates'],
            's'           => $settings,
        ]);
    }
}
