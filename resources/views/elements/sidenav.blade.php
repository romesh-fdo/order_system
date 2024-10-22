<nav class="navbar navbar-vertical navbar-expand-lg">
    @php
        $current_url = request()->url();
    @endphp

    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link nav-link-header label-1 {{ $current_url == route('manage.products') ? 'active' : '' }}" href="{{ route('manage.products') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center nav-link-header-text-tab">
                                <span class="nav-link-icon">
                                    <i class="fas fa-compass"></i>
                                </span>
                                <span class="nav-link-text-wrapper">
                                    <span class="nav-link-text nav-link-header-text">Products</span>
                                </span>
                            </div>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="navbar-vertical-footer">
        <button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center">
            <span class="uil uil-left-arrow-to-left fs-8"></span>
            <span class="uil uil-arrow-from-right fs-8"></span>
            <span class="navbar-vertical-footer-text ms-2">Collapsed View</span>
        </button>
    </div>
</nav>
