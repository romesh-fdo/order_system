<nav class="navbar navbar-vertical navbar-expand-lg">
    @php
        $current_url = request()->url();
        $role_code = Auth::user()->role->code ?? null;
        $is_super_admin = $role_code === \App\Models\Role::SUPER_ADMIN;
        $is_organization_admin = $role_code === \App\Models\Role::ORGANIZATION_ADMIN;
        $is_employee = $role_code === \App\Models\Role::EMPLOYEE;
        //$is_read_only = $role_code === \App\Models\Role::READ_ONLY;
        $is_contractor = $role_code === \App\Models\Role::CONTRACTOR;
        use App\Helpers\Helper;
        use App\Models\Role;
    @endphp

    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                @if ($is_super_admin)
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link dropdown-indicator label-1" href="#nv-administration"
                                role="button" data-bs-toggle="collapse" aria-expanded="{{ in_array($current_url, [route('organization'), route('activity_log'), route('access_log')]) ? 'true' : 'false' }}"
                                aria-controls="nv-administration">
                                <div class="d-flex align-items-center">
                                    <div class="dropdown-indicator-icon">
                                        <span class="fas fa-caret-right"></span>
                                    </div>
                                    <span class="nav-link-icon">
                                        <span data-feather="airplay"></span>
                                    </span>
                                    <span class="nav-link-text">Administration</span>
                                </div>
                            </a>
                            <div class="parent-wrapper label-1">
                                <ul class="nav collapse parent {{ in_array($current_url, [route('organization'), route('activity_log'), route('access_log')]) ? 'show' : '' }}" id="nv-administration">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('organization') ? 'active' : '' }}" href="{{ route('organization') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="far fa-building me-2"></span>Manage Organizations</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('activity_log') ? 'active' : '' }}" href="{{ route('activity_log') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="fas fa-hand-pointer me-2"></span>Activity Log</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('access_log') ? 'active' : '' }}" href="{{ route('access_log') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="fas fa-sign-in-alt me-2"></span>Access Log</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('marketing') ? 'active' : '' }}" href="{{ route('marketing') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="fas fa-bullhorn me-2"></span>Marketing</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                @endif
                @if ($is_super_admin || $is_organization_admin)
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link dropdown-indicator label-1" href="#nv-users"
                                role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ in_array($current_url, [route('employee'), route('employee_induction')]) ? 'true' : 'false' }}"
                                aria-controls="nv-users">
                                <div class="d-flex align-items-center">
                                    <div class="dropdown-indicator-icon">
                                        <span class="fas fa-caret-right"></span>
                                    </div>
                                    <span class="nav-link-icon">
                                        <span data-feather="users"></span>
                                    </span>
                                    <span class="nav-link-text">Users</span>
                                </div>
                            </a>
                            <div class="parent-wrapper label-1">
                                <ul class="nav collapse parent {{ in_array($current_url, [route('employee'), route('employee_induction'), route('contractor'), route('contractor_induction')]) ? 'show' : '' }}" id="nv-users">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('employee') ? 'active' : '' }}" href="{{ route('employee') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="far fa-user-circle me-2"></span>Manage Employees</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('employee_induction') ? 'active' : '' }}" href="{{ route('employee_induction') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="far fa-file-alt me-2"></span>Employee Inductions</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('contractor') ? 'active' : '' }}" href="{{ route('contractor') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="far fa-user me-2"></span>Contractors</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('contractor_induction') ? 'active' : '' }}" href="{{ route('contractor_induction') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="far fa-file-archive me-2"></span>Contractor Inductions</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link dropdown-indicator label-1" href="#nv-trainings"
                                role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ in_array($current_url, [route('employee'), route('employee_induction')]) ? 'true' : 'false' }}"
                                aria-controls="nv-trainings">
                                <div class="d-flex align-items-center">
                                    <div class="dropdown-indicator-icon">
                                        <span class="fas fa-caret-right"></span>
                                    </div>
                                    <span class="nav-link-icon">
                                        <span data-feather="tool"></span>
                                    </span>
                                    <span class="nav-link-text">Trainings</span>
                                </div>
                            </a>
                            <div class="parent-wrapper label-1">
                                <ul class="nav collapse parent {{ in_array($current_url, [route('certificates'), route('assessments')]) ? 'show' : '' }}" id="nv-trainings">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('certificates') ? 'active' : '' }}" href="{{ route('certificates') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="fa fa-certificate me-2"></span>Certificates</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('assessments') ? 'active' : '' }}" href="{{ route('assessments') }}" aria-disabled="true" >
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="far fa-file-alt me-2"></span>Assessments</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    @if (!$is_super_admin)
                        <li class="nav-item">
                            <div class="nav-item-wrapper">
                                <a class="nav-link dropdown-indicator label-1" href="#nv-review_forms"
                                    role="button" data-bs-toggle="collapse" aria-expanded="{{ in_array($current_url, [route('contractor_induction_form'), route('employee_induction_form'), route('site_assessment_form')]) ? 'true' : 'false' }}"
                                    aria-controls="nv-review_forms">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon">
                                            <span class="fas fa-caret-right"></span>
                                        </div>
                                        <span class="nav-link-icon">
                                            <span data-feather="check-circle"></span>
                                        </span>
                                        <span class="nav-link-text">Induction / Assessment Forms</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent {{ in_array($current_url, [route('contractor_induction_form'), route('employee_induction_form'), route('site_assessment_form')]) ? 'show' : '' }}" id="nv-review_forms">
                                        <li class="nav-item">
                                            <a class="nav-link {{ $current_url == route('employee_induction_form') ? 'active' : '' }}" href="{{ route('employee_induction_form') }}">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text"><span class="far fa-file-alt me-2"></span>Employee Induction Form</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ $current_url == route('contractor_induction_form') ? 'active' : '' }}" href="{{ route('contractor_induction_form') }}">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text"><span class="far fa-file-alt me-2"></span>Contractor Induction Form</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ $current_url == route('site_assessment_form') ? 'active' : '' }}" href="{{ route('site_assessment_form') }}">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text"><span class="far fa-file-alt me-2"></span>Site Assessment Form</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endif
                @endif
                @if ($is_super_admin || $is_organization_admin || $is_employee || $is_contractor)
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link nav-link-header label-1 {{ $current_url == route('incidents') ? 'active' : '' }}" href="{{ route('incidents') }}" role="button" data-bs-toggle="" aria-expanded="false">
                                <div class="d-flex align-items-center nav-link-header-text-tab">
                                    <span class="nav-link-icon">
                                        <i class="fas fa-compass"></i>
                                    </span>
                                    <span class="nav-link-text-wrapper">
                                        <span class="nav-link-text nav-link-header-text">Incidents</span>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link nav-link-header label-1 {{ $current_url == route('hazards') ? 'active' : '' }}" href="{{ route('hazards') }}" role="button" data-bs-toggle="" aria-expanded="false">
                                <div class="d-flex align-items-center nav-link-header-text-tab">
                                    <span class="nav-link-icon">
                                        <i class="fas fa-radiation"></i>
                                    </span>
                                    <span class="nav-link-text-wrapper">
                                        <span class="nav-link-text nav-link-header-text">Hazards</span>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link nav-link-header label-1 {{ $current_url == route('policies') ? 'active' : '' }}" href="{{ route('policies') }}" role="button" data-bs-toggle="" aria-expanded="false">
                                <div class="d-flex align-items-center nav-link-header-text-tab">
                                    <span class="nav-link-icon">
                                        <i class="fa-solid fa-landmark"></i>
                                    </span>
                                    <span class="nav-link-text-wrapper">
                                        <span class="nav-link-text nav-link-header-text">Policies</span>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link dropdown-indicator label-1" href="#nv-reports"
                                role="button" data-bs-toggle="collapse" aria-expanded="{{ in_array($current_url, [route('hazard_report'), route('incident_report')]) ? 'true' : 'false' }}"
                                aria-controls="nv-reports">
                                <div class="d-flex align-items-center">
                                    <div class="dropdown-indicator-icon">
                                        <span class="fas fa-caret-right"></span>
                                    </div>
                                    <span class="nav-link-icon">
                                        <span data-feather="pie-chart"></span>
                                    </span>
                                    <span class="nav-link-text">Reports</span>
                                </div>
                            </a>
                            <div class="parent-wrapper label-1">
                                <ul class="nav collapse parent {{ in_array($current_url, [route('hazard_report'), route('incident_report')]) ? 'show' : '' }}" id="nv-reports">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('hazard_report') ? 'active' : '' }}" href="{{ route('hazard_report') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="fa-solid fa-chart-line me-2"></span>Hazard Report</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('incident_report') ? 'active' : '' }}" href="{{ route('incident_report') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><i class="fa-solid fa-chart-line me-2"></i></span>Incident Report</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link dropdown-indicator label-1" href="#nv-sites"
                                role="button" data-bs-toggle="collapse" aria-expanded="{{ in_array($current_url, [route('sites'), route('site_assessments'), route('evacuations')]) ? 'true' : 'false' }}"
                                aria-controls="nv-sites">
                                <div class="d-flex align-items-center">
                                    <div class="dropdown-indicator-icon">
                                        <span class="fas fa-caret-right"></span>
                                    </div>
                                    <span class="nav-link-icon">
                                        <span data-feather="map-pin"></span>
                                    </span>
                                    <span class="nav-link-text">Sites</span>
                                </div>
                            </a>
                            <div class="parent-wrapper label-1">
                                <ul class="nav collapse parent {{ in_array($current_url, [route('sites'), route('site_assessment.index')]) ? 'show' : '' }}" id="nv-sites">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('sites') ? 'active' : '' }}" href="{{ route('sites') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="fas fa-map-marked-alt me-2"></span>Manage Sites</span>
                                            </div>
                                        </a>
                                    </li>
                                    @if(Helper::checkAuth(Role::SUPER_ADMIN) || Helper::checkAuth(Role::ORGANIZATION_ADMIN) || Auth::user()->is_site_manager)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('site_assessment.index') ? 'active' : '' }}" href="{{ route('site_assessment.index') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><span class="far fa-file-alt me-2"></span>Site Assessments</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $current_url == route('evacuations') ? 'active' : '' }}" href="{{ route('evacuations') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text"><i class="fa-solid fa-person-through-window me-2"></i></span>Evacuations</span>
                                            </div>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </li>
                @endif
                @if ($is_super_admin || $is_organization_admin)
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link nav-link-header label-1 {{ $current_url == route('complaints') ? 'active' : '' }}" href="{{ route('complaints') }}" role="button" data-bs-toggle="" aria-expanded="false">
                                <div class="d-flex align-items-center nav-link-header-text-tab">
                                    <span class="nav-link-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </span>
                                    <span class="nav-link-text-wrapper">
                                        <span class="nav-link-text nav-link-header-text">Complaints</span>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-item-wrapper">
                            <a class="nav-link nav-link-header label-1 {{ $current_url == route('settings') ? 'active' : '' }}" href="{{ route('settings') }}" role="button" data-bs-toggle="" aria-expanded="false">
                                <div class="d-flex align-items-center nav-link-header-text-tab">
                                    <span class="nav-link-icon">
                                        <i class="fas fa-cog"></i>
                                    </span>
                                    <span class="nav-link-text-wrapper">
                                        <span class="nav-link-text nav-link-header-text">Settings</span>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </li>
                    {{-- @if ($is_organization_admin)
                        <li class="nav-item">
                            <div class="nav-item-wrapper">
                                <a class="nav-link nav-link-header label-1 {{ $current_url == route('subscription') ? 'active' : '' }}" href="{{ route('subscription') }}" role="button" data-bs-toggle="" aria-expanded="false">
                                    <div class="d-flex align-items-center nav-link-header-text-tab">
                                        <span class="nav-link-icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </span>
                                        <span class="nav-link-text-wrapper">
                                            <span class="nav-link-text nav-link-header-text">Subscription Management</span>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </li>
                    @endif --}}
                @endif
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
