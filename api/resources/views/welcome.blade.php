{{-- resources/views/welcome.blade.php --}}
@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Storage;

    $checks = [];

    // Database check
    try {
        DB::connection()->getPdo();
        $checks['Database Connection'] = true;
    } catch (\Throwable $e) {
        $checks['Database Connection'] = false;
    }

    // Cache check
    try {
        Cache::put('health_check_key', 'ok', 5);
        $checks['Cache'] = Cache::get('health_check_key') === 'ok';
    } catch (\Throwable $e) {
        $checks['Cache'] = false;
    }

    // Storage check
    try {
        $checks['Storage (local disk)'] = Storage::disk('local')->exists('/') !== null;
    } catch (\Throwable $e) {
        $checks['Storage (local disk)'] = false;
    }

    // App key check
    $checks['APP_KEY Configured'] = !empty(config('app.key')) && config('app.key') !== 'SomeRandomString';

    // Queue configuration check (basic)
    $checks['Queue Configured'] = !empty(config('queue.default'));

    $isOnline = !in_array(false, $checks, true);

    $laravelVersion = app()->version();
    $phpVersion     = PHP_VERSION;
    $environment    = app()->environment();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>API Status | {{ config('app.name', 'MamoKacha API') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --bg: #020617;
            --card-bg: #0f172a;
            --card-bg-soft: #020617;
            --accent: #22c55e;
            --accent-soft: rgba(34, 197, 94, 0.15);
            --danger: #ef4444;
            --danger-soft: rgba(239, 68, 68, 0.15);
            --border: #1f2937;
            --text-main: #e5e7eb;
            --text-soft: #9ca3af;
            --chip-bg: #111827;
            --chip-border: #1f2937;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #0f172a, #020617 40%);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .shell {
            width: 100%;
            max-width: 960px;
        }

        .card {
            background: linear-gradient(145deg, var(--card-bg), #020617);
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow:
                0 24px 50px rgba(15, 23, 42, 0.9),
                0 0 0 1px rgba(15, 23, 42, 0.8);
            padding: 24px 28px 26px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 14px;
        }

        .logo {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: radial-gradient(circle at 0 0, #4ade80, #22c55e);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 35px rgba(34, 197, 94, 0.35);
        }

        .logo span {
            font-weight: 700;
            font-size: 1.2rem;
            color: #022c22;
        }

        .title-wrap {
            flex: 1;
        }

        .title {
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            margin: 0;
        }

        .subtitle {
            margin: 4px 0 0;
            font-size: 0.9rem;
            color: var(--text-soft);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 13px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .status-online {
            background: var(--accent-soft);
            color: var(--accent);
            border: 1px solid rgba(34, 197, 94, 0.35);
        }

        .status-offline {
            background: var(--danger-soft);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.35);
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: currentColor;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15);
        }

        .status-offline .status-dot {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 2.1fr) minmax(0, 1.4fr);
            gap: 18px;
            margin-top: 18px;
        }

        @media (max-width: 780px) {
            .card {
                padding: 20px 18px 22px;
            }
            .grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        .section {
            border-radius: 18px;
            border: 1px solid var(--border);
            padding: 14px 14px 12px;
            background: radial-gradient(circle at top left, rgba(15, 23, 42, 0.6), var(--card-bg-soft));
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 0.92rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title span.icon {
            width: 18px;
            height: 18px;
            border-radius: 8px;
            background: rgba(148, 163, 184, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .section-note {
            font-size: 0.78rem;
            color: var(--text-soft);
        }

        .checklist {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .check-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 7px 6px;
            border-radius: 10px;
            font-size: 0.86rem;
            gap: 10px;
        }

        .check-item:nth-child(odd) {
            background: rgba(15, 23, 42, 0.75);
        }

        .check-item:nth-child(even) {
            background: rgba(15, 23, 42, 0.4);
        }

        .check-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 9px;
            border-radius: 999px;
            font-size: 0.75rem;
            border: 1px solid var(--chip-border);
            background: var(--chip-bg);
            color: var(--text-soft);
        }

        .chip span.dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--text-soft);
        }

        .check-icon {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .check-icon.ok {
            background: var(--accent-soft);
            color: var(--accent);
            border: 1px solid rgba(34, 197, 94, 0.45);
        }

        .check-icon.fail {
            background: var(--danger-soft);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.45);
        }

        .meta-list {
            list-style: none;
            padding: 0;
            margin: 4px 0 0;
            font-size: 0.82rem;
            color: var(--text-soft);
        }

        .meta-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 0;
        }

        .meta-label {
            opacity: 0.9;
        }

        .meta-value {
            font-weight: 500;
            color: #e5e7eb;
        }

        .footer {
            margin-top: 14px;
            font-size: 0.78rem;
            color: var(--text-soft);
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #4ade80;
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="card">
        <div class="card-header">
            <div class="logo">
                <span>API</span>
            </div>

            <div class="title-wrap">
                <h1 class="title">
                    {{ config('app.name', 'MamoKacha API') }} Status
                </h1>
                <p class="subtitle">
                    Real-time health check for your API and core services.
                </p>
            </div>

            <div>
                @if($isOnline)
                    <div class="status-pill status-online">
                        <span class="status-dot"></span> Online
                    </div>
                @else
                    <div class="status-pill status-offline">
                        <span class="status-dot"></span> Offline
                    </div>
                @endif
            </div>
        </div>

        <div class="grid">
            <section class="section">
                <div class="section-header">
                    <div class="section-title">
                        <span class="icon">✓</span>
                        <span>Health Checklist</span>
                    </div>
                    <span class="section-note">
                        {{ $isOnline ? 'All systems are passing.' : 'One or more checks are failing.' }}
                    </span>
                </div>

                <ul class="checklist">
                    @foreach($checks as $label => $ok)
                        <li class="check-item">
                            <span class="check-label">
                                <span class="check-icon {{ $ok ? 'ok' : 'fail' }}">
                                    {{ $ok ? '✓' : '!' }}
                                </span>
                                {{ $label }}
                            </span>
                            <span class="chip">
                                <span class="dot"></span>
                                {{ $ok ? 'Working' : 'Not Working' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="section">
                <div class="section-header">
                    <div class="section-title">
                        <span class="icon">ⓘ</span>
                        <span>Environment Info</span>
                    </div>
                </div>

                <ul class="meta-list">
                    <li>
                        <span class="meta-label">Laravel Version</span>
                        <span class="meta-value">{{ $laravelVersion }}</span>
                    </li>
                    <li>
                        <span class="meta-label">PHP Version</span>
                        <span class="meta-value">{{ $phpVersion }}</span>
                    </li>
                    <li>
                        <span class="meta-label">Environment</span>
                        <span class="meta-value">{{ ucfirst($environment) }}</span>
                    </li>
                    <li>
                        <span class="meta-label">Time</span>
                        <span class="meta-value">{{ now()->toDateTimeString() }}</span>
                    </li>
                </ul>
            </section>
        </div>

        <div class="footer">
            <span class="badge">
                <span class="badge-dot"></span>
                Health endpoint: <code>/</code>
            </span>
            <span>
                This API is designed and developed by <a href="https://bitappstech.com/" target="_blank">BitApps Tech</a>
            </span>
        </div>
    </div>
</div>
</body>
</html>
