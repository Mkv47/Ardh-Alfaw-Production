<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') – أرض الفاو</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Cairo', sans-serif;
            background: #0a1628;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 60% 40%, rgba(0,168,150,.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 20% 80%, rgba(0,41,87,.4) 0%, transparent 50%);
        }
        .err-wrap { position: relative; z-index: 1; max-width: 520px; }
        .err-code {
            font-size: clamp(5rem, 20vw, 9rem);
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #00a896, #0077b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        .err-icon {
            font-size: 2rem;
            color: #00a896;
            margin-bottom: 16px;
        }
        .err-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #e2e8f0;
        }
        .err-desc {
            font-size: 1rem;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 32px;
        }
        .err-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-home {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #00a896, #0077b6);
            color: #fff; text-decoration: none;
            padding: 12px 28px; border-radius: 30px;
            font-family: inherit; font-size: .95rem; font-weight: 700;
            transition: opacity .2s, transform .2s;
        }
        .btn-home:hover { opacity: .88; transform: translateY(-2px); }
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent; color: #94a3b8;
            text-decoration: none; padding: 12px 24px; border-radius: 30px;
            border: 1px solid #334155;
            font-family: inherit; font-size: .95rem; font-weight: 600;
            cursor: pointer; transition: border-color .2s, color .2s;
        }
        .btn-back:hover { border-color: #00a896; color: #fff; }
        .err-logo { font-size: 1rem; color: #475569; margin-top: 48px; }
    </style>
</head>
<body>
<div class="err-wrap">
    <div class="err-code">@yield('code')</div>
    <div class="err-icon"><i class="@yield('icon', 'fas fa-exclamation-circle')"></i></div>
    <h1 class="err-title">@yield('title')</h1>
    <p class="err-desc">@yield('description')</p>
    <div class="err-actions">
        <button class="btn-back" onclick="history.back()"><i class="fas fa-arrow-right"></i> رجوع</button>
        <a href="/" class="btn-home"><i class="fas fa-home"></i> الصفحة الرئيسية</a>
    </div>
    <p class="err-logo"><i class="fas fa-anchor"></i> شركة أرض الفاو</p>
</div>
</body>
</html>
