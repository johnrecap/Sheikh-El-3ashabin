<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- REQUIRED META TAGS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="facebook-domain-verification" content="oeiigzidn8l0zji7ize6hmuxqh9f33" />

    <!-- GOOGLE FONTS - POPPINS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CUSTOM STYLE -->
    @vite('resources/css/navbar.css')
    @vite('resources/css/tailwind.css')
    @vite('resources/css/chatfile.css')
    @vite('resources/css/custom.css')
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">
    <!-- PAGE TITLE -->
    <title>{{ Settings::group('company')->get('company_name') }}</title>

    <!-- FAV ICON -->
    <link rel="icon" type="image" href="{{ $favicon }}">

    @if (!blank($analytics))
    @foreach ($analytics as $analytic)
    @if (!blank($analytic->analyticSections))
    @foreach ($analytic->analyticSections as $section)
    @if ($section->section == \App\Enums\AnalyticSection::HEAD)
    {!! $section->data !!}
    @endif
    @endforeach
    @endif
    @endforeach
    @endif

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1160701489564011');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1160701489564011&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
</head>

<body>
    @if (!blank($analytics))
    @foreach ($analytics as $analytic)
    @if (!blank($analytic->analyticSections))
    @foreach ($analytic->analyticSections as $section)
    @if ($section->section == \App\Enums\AnalyticSection::BODY)
    {!! $section->data !!}
    @endif
    @endforeach
    @endif
    @endforeach
    @endif

    <div id="app"></div>

    @if (!blank($analytics))
    @foreach ($analytics as $analytic)
    @if (!blank($analytic->analyticSections))
    @foreach ($analytic->analyticSections as $section)
    @if ($section->section == \App\Enums\AnalyticSection::FOOTER)
    {!! $section->data !!}
    @endif
    @endforeach
    @endif
    @endforeach
    @endif

    <script>
        const APP_URL = "{{ env('VITE_HOST') }}";
        const APP_DEMO = "{{ env('VITE_DEMO') }}";
        const APP_KEY = "{{ env('VITE_API_KEY') }}";
        const GOOGLE_TOKEN = "{{ env('VITE_GOOGLE_MAP_KEY') }}";
    </script>

    @vite('resources/js/app.js')
    <script src="{{ asset('themes/default/js/custom.js') }}"></script>

</body>

</html>