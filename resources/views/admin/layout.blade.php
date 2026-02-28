<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'لوحة التحكم') - أرض الفاو</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>

<div class="admin-wrapper">

    {{-- Sidebar --}}
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="fas fa-anchor"></i>
            <span>أرض الفاو</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> لوحة التحكم
            </a>

            <div class="sidebar-section">المحتوى</div>

            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i> الإعدادات العامة
            </a>
            <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i> الخدمات
            </a>
            <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> المشاريع
            </a>
            <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> الأخبار
            </a>
            <a href="{{ route('admin.team.index') }}" class="{{ request()->routeIs('admin.team*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> فريق العمل
            </a>
            <a href="{{ route('admin.tenders.index') }}" class="{{ request()->routeIs('admin.tenders*') ? 'active' : '' }}">
                <i class="fas fa-file-contract"></i> المناقصات
            </a>
            <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i> العملاء
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> معرض الصور
            </a>

            <div class="sidebar-section">الرسائل</div>

            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> رسائل التواصل
                @php $unread = \App\Models\ContactMessage::where('is_read',false)->count() @endphp
                @if($unread > 0)
                    <span style="background:var(--teal);color:#fff;padding:2px 8px;border-radius:10px;font-size:.75rem;margin-right:auto">{{ $unread }}</span>
                @endif
            </a>

            <div class="sidebar-section">الموقع</div>
            <a href="{{ route('home') }}" target="_blank">
                <i class="fas fa-external-link-alt"></i> عرض الموقع
            </a>
        </nav>
    </aside>

    {{-- Overlay for mobile sidebar --}}
    <div id="sidebarOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:150" onclick="closeSidebar()"></div>

    {{-- Main --}}
    <main class="admin-main">
        <header class="admin-header">
            <div style="display:flex;align-items:center;gap:14px">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>@yield('page-title', 'لوحة التحكم')</h1>
            </div>
            <div class="header-actions">
                <span class="admin-user">
                    <i class="fas fa-user-shield"></i>
                    <span class="admin-user-name">{{ auth()->user()->name }}</span>
                </span>
                <form action="{{ route('admin.logout') }}" method="POST" style="margin:0">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i><span class="btn-logout-text"> خروج</span>
                    </button>
                </form>
            </div>
        </header>

        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    const s = document.getElementById('adminSidebar');
    const o = document.getElementById('sidebarOverlay');
    s.classList.toggle('open');
    o.style.display = s.classList.contains('open') ? 'block' : 'none';
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').style.display = 'none';
}
</script>
</body>
</html>
