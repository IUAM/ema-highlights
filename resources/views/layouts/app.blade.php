<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="kMzbIa-aqIRJrt7Rs0qRWka7a0bEsmyg-rG25il3ND0" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- For migrating servers - IE needs http:// -->
    <base href="{!! config('app.url') !!}" />

    <!-- Scripts -->
    <script src="{{ asset('js/vendor.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.iu.edu/style.css?family=BentonSans:regular,bold|BentonSansCond:regular" media="screen" rel="stylesheet" type="text/css"/>

    <!-- Styles -->
    <link href="{{ asset('css/vendor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Google Analytics -->
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-89717795-1', 'auto');
    ga('send', 'pageview');
    </script>
</head>
<body>

<div id="branding-bar">
    <img src="images/trident-large.png" alt="IU">
    <div id="branding-row">
        <p id="iu-campus">
            <a href="https://www.indiana.edu" title="Indiana University Bloomington">
                <span class="show-on-desktop" itemprop="name">Indiana University Bloomington</span>
                <span class="show-on-tablet" itemprop="name">IU Bloomington</span>
                <span class="show-on-mobile" itemprop="name">IUB</span>
            </a>
        </p>
    </div>
</div>

<div id="sidebar">

    <div id="header">

        <a id="logo" href="https://artmuseum.indiana.edu">
            <img src="images/ema-logo-white.png" />
        </a>

        <a id="hamburger" href="javascript:;" class="btn-mobile">
            <span class="glyphicon glyphicon-th-list"></span>
        </a>

    </div>

    <form id="search" action="view/search" method="get">
        <div class="input-group">
            <input type="text" class="form-control input-sm" placeholder="Search the collection" name="q">
            <span class="input-group-btn">
                <button class="btn btn-sm" type="submit" id="search-submit"><span class="glyphicon glyphicon-search"></span></button>
            </span>
        </div>
    </form>

    @if (isset($sidebar))
    <div id="menu" class="menu menu-sidebar">
        <ul>
            <li>
                <a href="view/rights">Rights & Reproductions</a>
            </li>
        </ul>
    </div>
    @endif

</div>

<main id="main">
    @yield('content')
</main>

<footer id="footer">

    <p class="tagline">Fulfilling <span>the</span> Promise</p>

    <p class="signature">
        <a href="https://www.iu.edu" class="signature-link">Indiana University</a>
    </p>

    <p class="copyright">
    <span class="line-break-small"><a href="https://www.iu.edu/copyright/index.html">Copyright</a> &#169; 2016</span> <span class="line-break-small">The Trustees of <a href="https://www.iu.edu/" itemprop="url"><span itemprop="name">Indiana University</span></a></span><span class="hide-on-mobile"> | </span><span class="line-break"><a href="https://artmuseum.indiana.edu/privacy" id="privacy-policy-link">Privacy Notice</a> | <a href="https://accessibility.iu.edu" id="accessibility-link" title="Having trouble accessing this web page because of a disability? Visit this page for assistance.">Accessibility</a></span>
    </p>

</footer>

<!-- Litmus test for mobile screens -->
<div id="mobile"></div>

</body>
</html>
