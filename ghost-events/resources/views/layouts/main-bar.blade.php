<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="view-menu" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        {{ __('events.views') }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="view-menu">
                        <a class="dropdown-item" href="{{ route('events.list') }}">{{__('events.list')}}</a> {{----}}
                        <a class="dropdown-item" href="{{ route('events.map') }}">{{__('events.map')}}</a> {{----}}
                    </div>
                </li>
                @auth('web')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="user-menu" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        {{ __('events.menu') }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="user-menu">
                        <a class="dropdown-item" href="{{ route('events.userEvents') }}">{{__('events.my_events')}}</a>
                        <a class="dropdown-item"
                           href="{{ route('events.userAsGuestEvents') }}">{{__('events.attended_events')}}</a>
                        {{--<a class="dropdown-item" href="{{ route('events.creator') }}">{{__('events.new_event')}}</a>--}}
                    </div>
                </li>
                <li class="nav-item">

                    <a class="nav-link" href="{{ route('events.creator') }}">{{__('events.new_event')}} <i class="fas fa-plus"></i></a>
                </li>
                @endauth
            </ul>


            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @endguest
                @auth('web')

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>