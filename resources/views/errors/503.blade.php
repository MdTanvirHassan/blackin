<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Maintenance — Blackin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #050505;
            --gold: #b8860b;
            --gold-soft: #d4a84b;
            --gold-glow: rgba(184, 134, 11, 0.35);
            --cream: #f4efe6;
            --muted: rgba(244, 239, 230, 0.62);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--ink);
            color: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 55% at 50% 110%, rgba(184, 134, 11, 0.28), transparent 55%),
                radial-gradient(ellipse 45% 40% at 10% 15%, rgba(184, 134, 11, 0.14), transparent 50%),
                radial-gradient(ellipse 40% 35% at 90% 20%, rgba(212, 168, 75, 0.1), transparent 45%),
                linear-gradient(165deg, #000 0%, #0d0d0d 50%, #070707 100%);
        }

        .grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
            background-size: 72px 72px;
            mask-image: radial-gradient(ellipse 70% 60% at 50% 45%, black, transparent 75%);
            animation: grid-drift 28s linear infinite;
            pointer-events: none;
        }

        @keyframes grid-drift {
            from { transform: translateY(0); }
            to { transform: translateY(72px); }
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(184, 134, 11, 0.28);
            pointer-events: none;
        }

        .orb-1 {
            width: min(58vw, 520px);
            height: min(58vw, 520px);
            right: -12%;
            top: -18%;
            box-shadow: inset 0 0 80px rgba(184, 134, 11, 0.06);
            animation: spin 40s linear infinite;
        }

        .orb-2 {
            width: min(40vw, 340px);
            height: min(40vw, 340px);
            left: -8%;
            bottom: -12%;
            border-color: rgba(184, 134, 11, 0.18);
            animation: spin 55s linear infinite reverse;
        }

        .orb-3 {
            width: min(22vw, 180px);
            height: min(22vw, 180px);
            left: 18%;
            top: 22%;
            border-style: dashed;
            border-color: rgba(212, 168, 75, 0.25);
            animation: spin 22s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .dust {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .dust span {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--gold-soft);
            opacity: 0;
            animation: float-up linear infinite;
        }

        .dust span:nth-child(1) { left: 12%; animation-duration: 9s; animation-delay: 0s; }
        .dust span:nth-child(2) { left: 28%; width: 2px; height: 2px; animation-duration: 12s; animation-delay: 2s; }
        .dust span:nth-child(3) { left: 45%; animation-duration: 10s; animation-delay: 4s; }
        .dust span:nth-child(4) { left: 62%; width: 4px; height: 4px; animation-duration: 14s; animation-delay: 1s; }
        .dust span:nth-child(5) { left: 78%; animation-duration: 11s; animation-delay: 3.5s; }
        .dust span:nth-child(6) { left: 88%; width: 2px; height: 2px; animation-duration: 13s; animation-delay: 5s; }

        @keyframes float-up {
            0% { transform: translateY(100vh) scale(0.4); opacity: 0; }
            15% { opacity: 0.7; }
            85% { opacity: 0.35; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        .wrap {
            position: relative;
            z-index: 2;
            width: min(920px, 92vw);
            text-align: center;
            padding: 2rem 1rem;
        }

        .brand {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(2.8rem, 9vw, 5.75rem);
            font-weight: 600;
            letter-spacing: 0.08em;
            line-height: 0.95;
            margin-bottom: 1.75rem;
            opacity: 0;
            transform: translateY(30px);
            animation: rise 1s cubic-bezier(0.22, 1, 0.36, 1) 0.15s forwards;
        }

        .brand span {
            color: var(--gold-soft);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.55rem 1.1rem;
            border: 1px solid rgba(184, 134, 11, 0.4);
            background: rgba(184, 134, 11, 0.08);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--gold-soft);
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(20px);
            animation: rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.35s forwards;
        }

        .pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--gold);
            box-shadow: 0 0 0 0 var(--gold-glow);
            animation: pulse 1.8s ease-out infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 var(--gold-glow); }
            70% { box-shadow: 0 0 0 12px transparent; }
            100% { box-shadow: 0 0 0 0 transparent; }
        }

        h1 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(1.85rem, 4.5vw, 3.1rem);
            font-weight: 500;
            letter-spacing: 0.02em;
            line-height: 1.15;
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateY(24px);
            animation: rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.5s forwards;
        }

        .lead {
            font-size: clamp(0.95rem, 2vw, 1.1rem);
            font-weight: 300;
            line-height: 1.7;
            color: var(--muted);
            max-width: 38ch;
            margin: 0 auto 2.5rem;
            opacity: 0;
            transform: translateY(20px);
            animation: rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.65s forwards;
        }

        .gear-stage {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 2.25rem;
            opacity: 0;
            transform: translateY(16px) scale(0.92);
            animation: rise 1s cubic-bezier(0.22, 1, 0.36, 1) 0.4s forwards;
        }

        .gear {
            position: absolute;
            color: var(--gold);
            filter: drop-shadow(0 0 18px var(--gold-glow));
        }

        .gear-lg {
            width: 88px;
            height: 88px;
            left: 8px;
            top: 18px;
            animation: gear-cw 8s linear infinite;
        }

        .gear-sm {
            width: 52px;
            height: 52px;
            right: 10px;
            top: 8px;
            color: var(--gold-soft);
            animation: gear-ccw 5.5s linear infinite;
        }

        .gear-xs {
            width: 34px;
            height: 34px;
            right: 28px;
            bottom: 14px;
            opacity: 0.7;
            animation: gear-cw 4s linear infinite;
        }

        @keyframes gear-cw {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes gear-ccw {
            from { transform: rotate(0deg); }
            to { transform: rotate(-360deg); }
        }

        .line {
            width: 64px;
            height: 1px;
            margin: 0 auto 2rem;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0;
            animation: rise 0.8s ease 0.8s forwards;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem 2.5rem;
            opacity: 0;
            transform: translateY(16px);
            animation: rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.9s forwards;
        }

        .meta-item {
            text-align: center;
        }

        .meta-item strong {
            display: block;
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1.35rem;
            font-weight: 600;
            color: var(--gold-soft);
            letter-spacing: 0.04em;
            margin-bottom: 0.2rem;
        }

        .meta-item span {
            font-size: 0.68rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(244, 239, 230, 0.45);
        }

        .footer-note {
            position: absolute;
            bottom: 1.5rem;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(244, 239, 230, 0.28);
            z-index: 2;
            opacity: 0;
            animation: rise 0.8s ease 1.1s forwards;
        }

        @keyframes rise {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 575.98px) {
            .gear-stage {
                width: 120px;
                height: 120px;
            }

            .meta {
                gap: 1.25rem 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg"></div>
    <div class="grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="dust">
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
    </div>

    <main class="wrap">
        <div class="badge">
            <span class="pulse"></span>
            Under Maintenance
        </div>

        <p class="brand">Black<span>in</span></p>

        <div class="gear-stage" aria-hidden="true">
            <svg class="gear gear-lg" viewBox="0 0 100 100" fill="currentColor">
                <path d="M50 18c2.2 0 4.3.2 6.3.7l1.6 7.8c2.1.6 4 1.5 5.8 2.6l7.2-3.5c3.1 2.7 5.6 6 7.4 9.7l-5.4 5.9c.6 2 .9 4.1.9 6.3s-.3 4.3-.9 6.3l5.4 5.9c-1.8 3.7-4.3 7-7.4 9.7l-7.2-3.5c-1.8 1.1-3.7 2-5.8 2.6l-1.6 7.8c-2 .5-4.1.7-6.3.7s-4.3-.2-6.3-.7l-1.6-7.8c-2.1-.6-4-1.5-5.8-2.6l-7.2 3.5c-3.1-2.7-5.6-6-7.4-9.7l5.4-5.9c-.6-2-.9-4.1-.9-6.3s.3-4.3.9-6.3l-5.4-5.9c1.8-3.7 4.3-7 7.4-9.7l7.2 3.5c1.8-1.1 3.7-2 5.8-2.6l1.6-7.8c2-.5 4.1-.7 6.3-.7zm0 20a12 12 0 100 24 12 12 0 000-24z"/>
            </svg>
            <svg class="gear gear-sm" viewBox="0 0 100 100" fill="currentColor">
                <path d="M50 18c2.2 0 4.3.2 6.3.7l1.6 7.8c2.1.6 4 1.5 5.8 2.6l7.2-3.5c3.1 2.7 5.6 6 7.4 9.7l-5.4 5.9c.6 2 .9 4.1.9 6.3s-.3 4.3-.9 6.3l5.4 5.9c-1.8 3.7-4.3 7-7.4 9.7l-7.2-3.5c-1.8 1.1-3.7 2-5.8 2.6l-1.6 7.8c-2 .5-4.1.7-6.3.7s-4.3-.2-6.3-.7l-1.6-7.8c-2.1-.6-4-1.5-5.8-2.6l-7.2 3.5c-3.1-2.7-5.6-6-7.4-9.7l5.4-5.9c-.6-2-.9-4.1-.9-6.3s.3-4.3.9-6.3l-5.4-5.9c1.8-3.7 4.3-7 7.4-9.7l7.2 3.5c1.8-1.1 3.7-2 5.8-2.6l1.6-7.8c2-.5 4.1-.7 6.3-.7zm0 20a12 12 0 100 24 12 12 0 000-24z"/>
            </svg>
            <svg class="gear gear-xs" viewBox="0 0 100 100" fill="currentColor">
                <path d="M50 18c2.2 0 4.3.2 6.3.7l1.6 7.8c2.1.6 4 1.5 5.8 2.6l7.2-3.5c3.1 2.7 5.6 6 7.4 9.7l-5.4 5.9c.6 2 .9 4.1.9 6.3s-.3 4.3-.9 6.3l5.4 5.9c-1.8 3.7-4.3 7-7.4 9.7l-7.2-3.5c-1.8 1.1-3.7 2-5.8 2.6l-1.6 7.8c-2 .5-4.1.7-6.3.7s-4.3-.2-6.3-.7l-1.6-7.8c-2.1-.6-4-1.5-5.8-2.6l-7.2 3.5c-3.1-2.7-5.6-6-7.4-9.7l5.4-5.9c-.6-2-.9-4.1-.9-6.3s.3-4.3.9-6.3l-5.4-5.9c1.8-3.7 4.3-7 7.4-9.7l7.2 3.5c1.8-1.1 3.7-2 5.8-2.6l1.6-7.8c2-.5 4.1-.7 6.3-.7zm0 20a12 12 0 100 24 12 12 0 000-24z"/>
            </svg>
        </div>

        <h1>We’re polishing something better</h1>
        <p class="lead">
            Our store is temporarily closed while we upgrade the experience.
            Please check back soon — Blackin will return shortly.
        </p>

        <div class="line"></div>

        <div class="meta">
            <div class="meta-item">
                <strong>Soon</strong>
                <span>Back online</span>
            </div>
            <div class="meta-item">
                <strong>24/7</strong>
                <span>Working on it</span>
            </div>
            <div class="meta-item">
                <strong>Thank you</strong>
                <span>For your patience</span>
            </div>
        </div>
    </main>

    <p class="footer-note">Blackin — Crafted with care</p>
</body>
</html>
