<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    @include('elements.head')
</head>

<body>
    <main class="main" id="top">
        <div class="container">
            <div class="row flex-center min-vh-100 py-5">
                <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3">
                    <div class="text-center mb-7">
                        <h3 class="text-body-highlight">Sign In</h3>
                        <p class="text-body-tertiary">Get access to your account</p>
                    </div>
                    <form id="login_form">
                        @csrf
                        <div class="mb-3 text-start">
                            <label class="form-label" for="username">Username</label>
                            <div class="form-icon-container">
                                <input class="form-control form-icon-input" id="username" type="text" name="username" oninput="handleChange('error-username')" placeholder="username" />
                                <span class="fas fa-user text-body fs-9 form-icon"></span>
                                <small class="text-danger" id="error-username"></small>
                            </div>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label" for="password">Password</label>
                            <div class="form-icon-container">
                                <input class="form-control form-icon-input" id="password" type="password" name="password" oninput="handleChange('error-password')" placeholder="password" />
                                <span class="fas fa-key text-body fs-9 form-icon"></span>
                                <small class="text-danger" id="error-password"></small>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 mb-3" id="login_btn">Sign In</button>
                    </form>
                    <div id="section_loader" style="display: none;">
                        <h1 class="loader_icon"></h1>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('elements.js')
</body>
</html>

<script>
    $(document).ready(function() {
        $('#login_form').submit(async function(e) {
            e.preventDefault();

            const button_properties = {
                id: 'login_btn',
                text: 'Sign In',
                process_text: 'Processing ...',
            };

            url = '{{ route("login_process") }}';
            const response = await makeAPIRequest(new FormData(this), url, button_properties);

            if(response.success)
            {
                window.location.href = "{{ route('order') }}";
            }
        });
    });

</script>
