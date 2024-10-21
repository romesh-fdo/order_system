<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr">

@include('elements.head')

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
 
        @include('elements.sidenav')
        @include('elements.topnav')
        
        
        <div class="content">
            
            <div class="text-center">
                @if ($data['success'])
                    <div class="text-success">
                        <h1>{{$data['message']}}</h1>
                    </div>
                @else
                    <div class="text-danger">
                        <h1>{{$data['message']}}</h1>
                    </div>
                @endif

                @include('elements.footer')
            </div>
            
        </div>

    </main><!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

    {{-- @include('elements.themeselector') --}}

    @include('elements.js')

</body>

</html>
