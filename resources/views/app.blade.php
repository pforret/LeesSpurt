<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Letterly') }}</title>

        {{-- SEO Meta Tags --}}
        <meta name="description" content="Letterly - A fun reading trainer for kids aged 5-7. Learn to read words in English, Dutch, and French through interactive games.">
        <meta name="keywords" content="reading trainer, learn to read, kids education, phonics, literacy, English, Dutch, French, spelling">
        <meta name="author" content="Peter Forret">
        <meta name="robots" content="index, follow">
        <meta name="theme-color" content="#0ea5e9">

        {{-- Open Graph / Facebook --}}
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ config('app.url') }}">
        <meta property="og:title" content="{{ config('app.name', 'Letterly') }} - Reading Trainer for Kids">
        <meta property="og:description" content="A fun reading trainer for kids aged 5-7. Learn to read words in English, Dutch, and French through interactive games.">
        <meta property="og:image" content="{{ config('app.url') }}/og-image.png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:site_name" content="Letterly">
        <meta property="og:locale" content="en_US">
        <meta property="og:locale:alternate" content="nl_NL">
        <meta property="og:locale:alternate" content="fr_FR">

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ config('app.url') }}">
        <meta name="twitter:title" content="{{ config('app.name', 'Letterly') }} - Reading Trainer for Kids">
        <meta name="twitter:description" content="A fun reading trainer for kids aged 5-7. Learn to read words in English, Dutch, and French.">
        <meta name="twitter:image" content="{{ config('app.url') }}/og-image.png">
        <meta name="twitter:creator" content="@pforret">

        {{-- Structured Data for LLMs and Search --}}
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebApplication",
            "name": "Letterly",
            "description": "A reading skills trainer for children aged 5-7, supporting English, Dutch, and French languages.",
            "url": "{{ config('app.url') }}",
            "applicationCategory": "EducationalApplication",
            "operatingSystem": "Web",
            "offers": {
                "@@type": "Offer",
                "price": "0",
                "priceCurrency": "EUR"
            },
            "audience": {
                "@@type": "EducationalAudience",
                "educationalRole": "student",
                "suggestedMinAge": 5,
                "suggestedMaxAge": 7
            },
            "inLanguage": ["en", "nl", "fr"],
            "author": {
                "@@type": "Person",
                "name": "Peter Forret",
                "url": "https://github.com/pforret"
            }
        }
        </script>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|lugrasimo:400" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
