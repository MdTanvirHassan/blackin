@extends('frontend.layouts.app')

@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL($page->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
@php
    $lang = str_replace('_', '-', app()->getLocale());
    $content = json_decode($page->getTranslation('content', $lang));
    $brand = get_setting('website_name') ?: env('APP_NAME');
    $address = $content->address ?? '';
    $phone = $content->phone ?? '';
    $email = $content->email ?? '';
    $description = $content->description ?? '';
@endphp

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .cu-page {
        --cu-ink: #0a0a0a;
        --cu-ink-soft: #1a1a1a;
        --cu-gold: #b8860b;
        --cu-gold-soft: #d4a84b;
        --cu-cream: #f7f4ef;
        --cu-muted: #6b6560;
        --cu-line: rgba(184, 134, 11, 0.28);
        --cu-display: 'Cormorant Garamond', Georgia, serif;
        --cu-body: 'DM Sans', 'Public Sans', sans-serif;
        font-family: var(--cu-body);
        color: var(--cu-ink);
        background: var(--cu-cream);
        overflow: hidden;
    }

    .cu-hero {
        position: relative;
        min-height: min(72vh, 620px);
        display: flex;
        align-items: flex-end;
        background:
            radial-gradient(ellipse 80% 60% at 70% 20%, rgba(184, 134, 11, 0.22), transparent 55%),
            radial-gradient(ellipse 50% 40% at 15% 80%, rgba(184, 134, 11, 0.12), transparent 50%),
            linear-gradient(160deg, #000 0%, #141414 45%, #0a0a0a 100%);
        color: #fff;
        padding: 0 0 4.5rem;
    }

    .cu-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 64px 64px;
        mask-image: linear-gradient(to bottom, black 20%, transparent 90%);
        pointer-events: none;
    }

    .cu-hero::after {
        content: '';
        position: absolute;
        right: -8%;
        bottom: -20%;
        width: min(52vw, 520px);
        height: min(52vw, 520px);
        border-radius: 50%;
        border: 1px solid rgba(184, 134, 11, 0.25);
        box-shadow: inset 0 0 0 40px rgba(184, 134, 11, 0.03);
        animation: cu-orbit 18s linear infinite;
        pointer-events: none;
    }

    @keyframes cu-orbit {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .cu-hero-inner {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 1140px;
        margin: 0 auto;
        padding: 5.5rem 1.25rem 0;
    }

    .cu-brand {
        display: inline-block;
        font-family: var(--cu-display);
        font-size: clamp(2.75rem, 7vw, 5.5rem);
        font-weight: 600;
        letter-spacing: 0.04em;
        line-height: 0.95;
        color: #fff;
        margin: 0 0 1.25rem;
        opacity: 0;
        transform: translateY(28px);
        animation: cu-rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.1s forwards;
    }

    .cu-brand span {
        color: var(--cu-gold-soft);
    }

    .cu-hero h1 {
        font-family: var(--cu-display);
        font-size: clamp(1.75rem, 3.5vw, 2.75rem);
        font-weight: 500;
        letter-spacing: 0.02em;
        margin: 0 0 0.85rem;
        max-width: 16ch;
        opacity: 0;
        transform: translateY(24px);
        animation: cu-rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.25s forwards;
    }

    .cu-hero-lead {
        font-size: 1.05rem;
        line-height: 1.65;
        color: rgba(255, 255, 255, 0.72);
        max-width: 36ch;
        margin: 0 0 1.75rem;
        opacity: 0;
        transform: translateY(20px);
        animation: cu-rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.4s forwards;
    }

    .cu-hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.95rem 1.6rem;
        background: var(--cu-gold);
        color: #fff !important;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        text-decoration: none !important;
        transition: background 0.25s ease, transform 0.25s ease;
        opacity: 0;
        transform: translateY(16px);
        animation: cu-rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.55s forwards;
    }

    .cu-hero-cta:hover {
        background: var(--cu-gold-soft);
        color: #fff !important;
        transform: translateY(-2px);
    }

    .cu-hero-cta i {
        font-size: 1.1rem;
        transition: transform 0.25s ease;
    }

    .cu-hero-cta:hover i {
        transform: translateY(3px);
    }

    @keyframes cu-rise {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cu-main {
        max-width: 1140px;
        margin: -3.5rem auto 0;
        padding: 0 1.25rem 5rem;
        position: relative;
        z-index: 2;
    }

    .cu-panel {
        display: grid;
        grid-template-columns: 1fr 1.15fr;
        gap: 0;
        background: #fff;
        box-shadow: 0 24px 60px rgba(10, 10, 10, 0.12);
        overflow: hidden;
        opacity: 0;
        transform: translateY(32px);
        animation: cu-rise 0.85s cubic-bezier(0.22, 1, 0.36, 1) 0.35s forwards;
    }

    .cu-info {
        background: var(--cu-ink);
        color: #fff;
        padding: clamp(2rem, 4vw, 3.25rem);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 2.5rem;
        position: relative;
    }

    .cu-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(to bottom, var(--cu-gold), transparent);
    }

    .cu-info-label {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--cu-gold-soft);
        margin-bottom: 0.75rem;
    }

    .cu-info h2 {
        font-family: var(--cu-display);
        font-size: clamp(1.85rem, 3vw, 2.4rem);
        font-weight: 500;
        line-height: 1.15;
        margin: 0 0 0.75rem;
    }

    .cu-info-copy {
        color: rgba(255, 255, 255, 0.65);
        font-size: 0.95rem;
        line-height: 1.65;
        margin: 0;
    }

    .cu-channels {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .cu-channel {
        display: grid;
        grid-template-columns: 44px 1fr;
        gap: 1rem;
        align-items: start;
        padding: 1.15rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        text-decoration: none !important;
        color: inherit;
        transition: padding-left 0.25s ease;
    }

    .cu-channel:last-child {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    a.cu-channel:hover {
        padding-left: 0.4rem;
    }

    .cu-channel-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--cu-line);
        color: var(--cu-gold-soft);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .cu-channel-title {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.45);
        margin-bottom: 0.3rem;
    }

    .cu-channel-value {
        display: block;
        font-size: 0.98rem;
        line-height: 1.5;
        color: #fff;
        word-break: break-word;
    }

    .cu-form-wrap {
        padding: clamp(2rem, 4vw, 3.25rem);
        background:
            linear-gradient(180deg, #fff 0%, #faf8f5 100%);
    }

    .cu-form-wrap .cu-info-label {
        color: var(--cu-gold);
    }

    .cu-form-wrap h2 {
        font-family: var(--cu-display);
        font-size: clamp(1.85rem, 3vw, 2.4rem);
        font-weight: 500;
        line-height: 1.15;
        margin: 0 0 0.5rem;
        color: var(--cu-ink);
    }

    .cu-form-intro {
        color: var(--cu-muted);
        font-size: 0.95rem;
        margin: 0 0 1.75rem;
    }

    .cu-form .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .cu-field {
        margin-bottom: 1.15rem;
    }

    .cu-field label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--cu-ink-soft);
        margin-bottom: 0.45rem;
    }

    .cu-field input,
    .cu-field textarea {
        width: 100%;
        border: none;
        border-bottom: 1.5px solid #ddd6cc;
        background: transparent;
        border-radius: 0;
        padding: 0.75rem 0;
        font-family: var(--cu-body);
        font-size: 0.98rem;
        color: var(--cu-ink);
        outline: none;
        transition: border-color 0.25s ease, background 0.25s ease;
        box-shadow: none !important;
    }

    .cu-field textarea {
        resize: vertical;
        min-height: 110px;
    }

    .cu-field input:focus,
    .cu-field textarea:focus {
        border-bottom-color: var(--cu-gold);
        background: rgba(184, 134, 11, 0.03);
    }

    .cu-field input::placeholder,
    .cu-field textarea::placeholder {
        color: #a39e96;
    }

    .cu-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        min-width: 200px;
        padding: 1rem 1.75rem;
        margin-top: 0.5rem;
        border: none;
        background: var(--cu-ink);
        color: #fff !important;
        font-family: var(--cu-body);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.25s ease, transform 0.25s ease;
        text-decoration: none !important;
    }

    .cu-submit:hover {
        background: var(--cu-gold);
        color: #fff !important;
        transform: translateY(-2px);
    }

    .cu-submit i {
        font-size: 1.05rem;
        transition: transform 0.25s ease;
    }

    .cu-submit:hover i {
        transform: translateX(4px);
    }

    @media (max-width: 991.98px) {
        .cu-panel {
            grid-template-columns: 1fr;
        }

        .cu-hero {
            min-height: min(58vh, 480px);
            padding-bottom: 3.5rem;
        }

        .cu-main {
            margin-top: -2.5rem;
        }

        .cu-form .form-row-2 {
            grid-template-columns: 1fr;
        }

        .cu-hero::after {
            width: 280px;
            height: 280px;
            right: -20%;
        }
    }

    @media (max-width: 575.98px) {
        .cu-submit {
            width: 100%;
        }
    }
</style>

<section class="cu-page">
    <div class="cu-hero">
        <div class="cu-hero-inner">
            <p class="cu-brand">{{ $brand }}</p>
            <h1>{{ $page->getTranslation('title') }}</h1>
            <p class="cu-hero-lead">
                {{ $description ?: translate('We would love to hear from you. Reach out and our team will get back shortly.') }}
            </p>
            <a href="#cu-form" class="cu-hero-cta">
                {{ translate('Send a message') }}
                <i class="las la-arrow-down"></i>
            </a>
        </div>
    </div>

    <div class="cu-main">
        <div class="cu-panel">
            <aside class="cu-info">
                <div>
                    <div class="cu-info-label">{{ translate('Get in touch') }}</div>
                    <h2>{{ translate('Talk with our team') }}</h2>
                    <p class="cu-info-copy">{{ translate('Questions about orders, products, or partnerships — we are here to help.') }}</p>
                </div>

                <div class="cu-channels">
                    @if($address)
                        <div class="cu-channel">
                            <span class="cu-channel-icon"><i class="las la-map-marker-alt"></i></span>
                            <span>
                                <span class="cu-channel-title">{{ translate('Address') }}</span>
                                <span class="cu-channel-value">{!! nl2br(e($address)) !!}</span>
                            </span>
                        </div>
                    @endif

                    @if($phone)
                        <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="cu-channel">
                            <span class="cu-channel-icon"><i class="las la-phone"></i></span>
                            <span>
                                <span class="cu-channel-title">{{ translate('Phone') }}</span>
                                <span class="cu-channel-value">{{ $phone }}</span>
                            </span>
                        </a>
                    @endif

                    @if($email)
                        <a href="mailto:{{ $email }}" class="cu-channel">
                            <span class="cu-channel-icon"><i class="las la-envelope"></i></span>
                            <span>
                                <span class="cu-channel-title">{{ translate('Email Address') }}</span>
                                <span class="cu-channel-value">{{ $email }}</span>
                            </span>
                        </a>
                    @endif
                </div>
            </aside>

            <div class="cu-form-wrap" id="cu-form">
                <div class="cu-info-label">{{ translate('Contact form') }}</div>
                <h2>{{ translate('Send us a message') }}</h2>
                <p class="cu-form-intro">{{ translate('Fill in the details below and we will reply as soon as we can.') }}</p>

                <form class="cu-form form-default" id="contact-us" role="form" action="{{ route('contact') }}" method="POST">
                    @csrf

                    <div class="form-row-2">
                        <div class="cu-field">
                            <label for="name">{{ translate('Name') }}</label>
                            <input type="text" id="name" value="{{ old('name') }}" placeholder="{{ translate('Enter Name') }}" name="name" required>
                        </div>
                        <div class="cu-field">
                            <label for="email">{{ translate('Email') }}</label>
                            <input type="email" id="email" value="{{ old('email') }}" placeholder="{{ translate('Enter Email') }}" name="email" required>
                        </div>
                    </div>

                    <div class="cu-field">
                        <label for="phone">{{ translate('Phone no. (optional)') }}</label>
                        <input type="tel" id="phone" value="{{ old('phone') }}" placeholder="{{ translate('Enter Phone') }}" name="phone">
                    </div>

                    <div class="cu-field">
                        <label for="query">{{ translate('Tell us about your query') }}</label>
                        <textarea
                            id="query"
                            placeholder="{{ translate('Type here...') }}"
                            name="content"
                            rows="4"
                            required
                        >{{ old('content') }}</textarea>
                    </div>

                    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_contact_form') == 1)
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white" role="alert" style="display: block;">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    @endif

                    <div>
                        @if (env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
                            <a class="cu-submit" href="javascript:void(0)" onclick="showWarning()">
                                {{ translate('Submit') }}
                                <i class="las la-arrow-right"></i>
                            </a>
                        @else
                            <button type="submit" class="cu-submit">
                                {{ translate('Submit') }}
                                <i class="las la-arrow-right"></i>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
     @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_contact_form') == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>

        <script type="text/javascript">
                document.getElementById('contact-us').addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'contact_us'}).then(function(token) {
                            var input = document.createElement('input');
                            input.setAttribute('type', 'hidden');
                            input.setAttribute('name', 'g-recaptcha-response');
                            input.setAttribute('value', token);
                            e.target.appendChild(input);
                            e.target.submit();
                        });
                    });
                });
        </script>
    @endif

    <script type="text/javascript">
        function showWarning(){
            AIZ.plugins.notify('warning', "{{ translate('Something went wrong.') }}");
            return false;
        }
    </script>
@endsection
