<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            {{ config('app.name', 'blogProject') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto navbar-left">

                  @auth
                  <li class="nav-item">
                    <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                  </li>
                  @endauth

                  <li class="nav-item">
                    <a class="nav-link {{ Route::is('about.index') ? 'active' : '' }}" href="{{ route('about.index') }}">About</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ Route::is('services.index') ? 'active' : '' }}" href="{{ route('services.index') }}">Services</a>
                  </li>

                  @auth
                    @if(auth()->user()->email_verified_at != null)
                      <!-- Dropdown has active class with "or" condition. -->
                      <li class="nav-item dropdown {{ Route::is('posts.index') ? 'active' : '' ||  Route::is('posts.create') ? 'active' : '' }}">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Posts
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('posts.index') }}">All Posts</a>
                            @if ( auth()->user()->locked == 'unlocked' )
                              <a class="dropdown-item" href="{{ route('posts.create') }}">Create Posts</a>
                            @elseif ( auth()->user()->locked == 'locked' )
                              <a class="dropdown-item disabled" href="{{ route('posts.create') }}">Create Posts <span class="badge badge-danger">Locked</span></a>
                            @endif
                          </div>
                      </li>

                      <!-- Dropdown has active class with "or" condition. -->
                      <li class="nav-item dropdown {{ \Request::is('posts/myposts/'.auth()->user()->id) ? 'active' : '' || Route::is('photo.display') ? 'active' : '' || \Request::is('profile/display/'.auth()->user()->id) ? 'active' : '' }}">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Stuff
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('posts.myposts', auth()->user()->id) }}">My Posts</a>
                            <a class="dropdown-item" href="{{ route('photo.display', auth()->user()->id) }}">My Photo Gallery</a>
                            <a class="dropdown-item" href="{{ route('profile.display', auth()->user()->id) }}">My Profile</a>
                          </div>
                      <li class="nav-item">
                        <a class="nav-link {{ Route::is('messages.index') ? 'active' : '' }}" href="{{ route('messages.messagesPage', auth()->user()->id,) }}">See Messages List</a>
                      </li>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link {{ \Request::is('friendslist/'.auth()->user()->id) ? 'active' : '' }}" href="{{ route( 'messages.friendslist', auth()->user()->id ) }}">Friends List</a>
                      </li>

                        @if(auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin')
                          <li class="nav-item">
                            <a class="nav-link {{ Route::is('adminpanel.index') ? 'active' : '' }}" href="{{ route('adminpanel.index') }}">Admin Panel</a>
                          </li>
                        @endauth
                    @endif
                  @endauth
                </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">

                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            <a class="dropdown-item" href="{{ route('home') }}">
                                <p> Dashboard </p>
                            </a>

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
                @endguest
            </ul>
        </div>
    </div>
</nav>
