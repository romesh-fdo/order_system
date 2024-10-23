<nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
    <div class="collapse navbar-collapse justify-content-between">
        <div class="navbar-logo">
            <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent"
                type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
                aria-controls="navbarVerticalCollapse" aria-expanded="false"
                aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span
                        class="toggle-line"></span></span></button>
            <a class="navbar-brand me-1 me-sm-3" href="{{ route('dashboard') }}">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center"><img class="nav-logo" src="{{ url('/assets/img/icons/logo.png') }}"
                            alt="phoenix"/>
                    </div>
                </div>
            </a>
        </div>
        <ul class="navbar-nav navbar-nav-icons flex-row">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}">
                    <button class="btn btn-primary me-3">Dashboard</button>
                </a>
            </li>
            <li class="nav-item">
                <div class="theme-control-toggle fa-icon-wait px-2">
                    <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme">
                        <span class="icon" data-feather="moon"></span>
                    </label>
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme">
                        <span class="icon" data-feather="sun"></span>
                    </label>
                </div>
            </li>
            @php
                $profile_image_url = null;
            @endphp
            <li class="nav-item dropdown">
                <a class="nav-link lh-1 pe-0" id="navbarDropdownUser"
                    href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-l ">
                        @if($profile_image_url)
                            <img class="rounded-circle " src="{{$profile_image_url}}" alt="" />
                        @else
                            <i class="fas fa-user-circle fa-2x mt-2"></i>
                        @endif
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border"
                    aria-labelledby="navbarDropdownUser">
                    <div class="card position-relative border-0">
                        <div class="card-footer p-0 border-top border-translucent">
                            <div class="px-3 mt-3 mb-3"> <a
                                    class="btn btn-phoenix-secondary d-flex flex-center w-100"
                                    href="#!" onclick="logout()"> <span class="me-2" data-feather="log-out" id="btn_logout">
                                    </span>Sign out</a></div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script>
    async function logout()
    {
        var formData = new FormData();
        formData.append('remember_me_token', $.cookie('remember_me_token'));
        var url = '{{ route("logout") }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const response = await makeAPIRequest(formData, url, null);

        if(response.success)
        {
            if(response.redirect)
            {
                $(location).prop('href', response.redirect);
            }
        } 
        else 
        {
            $('#section_loader').hide();
            $('#login_form').show();
        }
    }
</script>