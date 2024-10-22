<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    @include('elements.head')
</head>

<body>
    <main class="main" id="top">
        @include('elements.sidenav')
        @include('elements.topnav')

        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12 col-xxl-6">
                            <h2 class="mb-2">Manage Products</h2>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

@include('elements.js')
