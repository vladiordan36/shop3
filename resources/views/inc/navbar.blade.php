<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="/index">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="/index">{{ __('messages.products') }}</a></li>
                <li>
                    <a href="/cart">{{ __('messages.cart') }}
                        <span class="badge">{{Session::has('cart') ? count(Session::get('cart')) : ''}}</span>
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                @if(Session::has('logged in'))
                    <li>
                        <a href="/admin">{{ __('messages.admin') }}</a>
                    </li>
                    <li>
                        <a href={{route('product.create')}}>{{ __('messages.add') }}</a>
                    </li>
                    <li>
                        <a href="/logout">{{ __('messages.logout') }}</a>
                    </li>
                @else
                    <li>
                        <a href="/login">{{ __('messages.login') }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
