@php
    use Carbon\Carbon;
    use App\Models\Notification;
    
    $user_notifications = Notification::where('for', Auth::user()->id)
                                ->where('is_read', false)
                                ->orderBy('created_at', 'desc')
                                ->get();
@endphp

<nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
    <div class="collapse navbar-collapse justify-content-between">
        <div class="navbar-logo">
            <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent"
                type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
                aria-controls="navbarVerticalCollapse" aria-expanded="false"
                aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span
                        class="toggle-line"></span></span></button>
            <a class="navbar-brand me-1 me-sm-3" href="{{ route('home') }}">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center"><img class="nav-logo" src="{{ url('/assets/img/icons/logo.png') }}"
                            alt="phoenix"/>
                    </div>
                </div>
            </a>
        </div>
        <ul class="navbar-nav navbar-nav-icons flex-row">
            <li class="nav-item">
                <a href="{{ route('home') }}">
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
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" style="min-width: 2.25rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
                    <span data-feather="bell" style="height:20px;width:20px;"></span>
                    @if (count($user_notifications) > 0)
                        <span class="position-absolute translate-middle badge rounded-pill bg-danger p-1 blink" style="width: 5px; height: 5px;">
                            <span class="visually-hidden">New notifications</span>
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                    <div class="card position-relative border-0">
                        <div class="card-header p-2">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-body-emphasis mb-0">Notificatons</h5>
                                <button id="mark-all-notifications" class="btn btn-link p-0 fs-9 fw-normal" type="button">
                                    Mark all as read
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="scrollbar-overlay" style="height: 27rem;">
                                @if (count($user_notifications) > 0)
                                    @foreach ($user_notifications as $user_notification)
                                        @php
                                            $notification_id = $user_notification['uuid'];
                                        @endphp
                                        <div class="px-2 px-sm-3 py-3 notification_unread notification-card position-relative read border-bottom notification-card-{{$notification_id}}">
                                            <div class="d-flex align-items-center justify-content-between position-relative">
                                                <div class="d-flex">
                                                    <div class="flex-1 me-sm-3">
                                                        <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal">
                                                            {{$user_notification['description']}}

                                                            @php
                                                                $difference = $user_notification['created_at']->diffForHumans([
                                                                    'short' => true,
                                                                    'parts' => 3,
                                                                ]);
                                                            @endphp

                                                            <span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">{{$difference}}</span>
                                                        </p>
                                                        <p class="text-body-secondary fs-9 mb-0">
                                                            <span class="me-1 fas fa-clock"></span>
                                                            <span class="fw-bold">
                                                                {{Carbon::parse($user_notification['created_at'])->format('jS \of F Y \a\t h.i A')}}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-none d-sm-block">
                                                    <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                                                        <span class="fas fa-ellipsis-h fs-10 text-body">
                                                        </span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2" id="read_notification_{{$notification_id}}">
                                                        <a class="dropdown-item" href="#!" onclick="changeNotificationStatus('{{$notification_id}}', 1)">Mark as read</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="d-flex justify-content-center align-items-center text-center" style="height: 50vh;">
                                        <div>
                                            <h4>No New Notifications</h4>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('notifications.index') }}">View All</a>
                        </div>
                    </div>
                </div>
            </li>
            @php
                $profile_image_url = null;
                if (Auth::user()->profile_picture) {
                   // $profile_image_url = route('view_file')."?key=".encrypt(Auth::user()->profile_picture);
                }
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
                        <div class="card-body p-0">
                            <div class="text-center pt-4 pb-3">
                                <div class="avatar avatar-xl ">
                                    @if($profile_image_url)
                                        <img class="rounded-circle " src="{{$profile_image_url}}" alt="" />
                                    @else
                                        <i class="fas fa-user-circle fa-2x mt-2"></i>
                                    @endif
                                </div>
                                <h6 class="mt-2 text-body-emphasis">{{Auth::user()->name}}</h6>
                            </div>
                        </div>
                        <hr>
                        <div class="overflow-auto scrollbar" >
                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                <li class="nav-item"><a class="nav-link px-3" href="{{route('add_complaints')}}"> <span
                                            class="me-2 text-body" data-feather="alert-circle"></span>Add Complaints</a></li>
                                <li class="nav-item"><a class="nav-link px-3" href="{{route('profile_settings')}}"> <span
                                            class="me-2 text-body" data-feather="settings"></span>Settings</a></li>
                            </ul>
                        </div>
                        <div class="card-footer p-0 border-top border-translucent">
                            <div class="px-3 mt-3"> <a
                                    class="btn btn-phoenix-secondary d-flex flex-center w-100"
                                    href="#!" onclick="logout()"> <span class="me-2" data-feather="log-out" id="btn_logout">
                                    </span>Sign out</a></div>
                            <div class="my-2 text-center fw-bold fs-10 text-body-quaternary"><a
                                    class="text-body-quaternary me-1" href="#!">Privacy
                                    policy</a>&bull;<a class="text-body-quaternary ms-1"
                                    href="#!">Terms</a></div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script>
    
    $(document).ready(function() {
        checkForUnreadNotifications();
        $('#mark-all-notifications').on('click', toggleMarkAllNotificationsStatus);
    });

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

        const response = await makeAjaxRequest(formData, url, null);

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

    function toggleMarkAllNotificationsStatus() {
        if ($('.notification-card.notification_unread').length > 0) {
            markAllNotificationsRead();
            $('#mark-all-notifications').text('Mark all as unread');
        } else {
            markAllNotificationsUnread();
            $('#mark-all-notifications').text('Mark all as read');
        }
    }

    async function markAllNotificationsRead() {
        await updateNotificationStatus(null, 1, true);
        $('.notification-card').removeClass('notification_unread').addClass('notification_read');
        checkForUnreadNotifications();
    }

    async function markAllNotificationsUnread() {
        await updateNotificationStatus(null, 0, true);
        $('.notification-card').removeClass('notification_read').addClass('notification_unread');
        checkForUnreadNotifications();
    }

    function checkForUnreadNotifications() {
        if ($('.notification-card.notification_unread').length > 0) {
            $('.nav-link .badge').show();
        } else {
            $('.nav-link .badge').hide();
        }
    }

    async function changeNotificationStatus(notification_id, status) {
        await updateNotificationStatus(notification_id, status);
        var notificationCard = $('.notification-card-' + notification_id);

        if (status) {
            notificationCard.removeClass('notification_unread').addClass('notification_read');
            $("#read_notification_" + notification_id + " .dropdown-item")
                .attr('onclick', `changeNotificationStatus('${notification_id}', 0)`)
                .text("Mark as unread");
        } else {
            notificationCard.removeClass('notification_read').addClass('notification_unread');
            $("#read_notification_" + notification_id + " .dropdown-item")
                .attr('onclick', `changeNotificationStatus('${notification_id}', 1)`)
                .text("Mark as read");
        }

        // Check if all notifications are read or unread and update the button text accordingly
        if ($('.notification-card.notification_unread').length === 0) {
            $('#mark-all-notifications').text('Mark all as unread');
        } else {
            $('#mark-all-notifications').text('Mark all as read');
        }

        checkForUnreadNotifications();
    }

    async function updateNotificationStatus(notification_id = null, status = 1, all = false) {
        var formData = new FormData();
        formData.append('notification_id', notification_id);
        formData.append('status', status);
        formData.append('all', all);
        var url = '{{ route("update_notification_status") }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        return await makeAjaxRequest(formData, url, null);
    }
</script>