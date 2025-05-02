{{-- <!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete
                        It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> --}}
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="" class="logo logo-light mt-2">
            <span class="logo-lg">
                <h3 class="text-white ">Bathi Worksentry</h3>
            </span>

            <span class="logo-sm">
                <h3 class="text-white">BW</h3>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <div class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}"
                        href="{{route('dashboard.index')}}" aria-expanded="false">
                        <i class=" ri-dashboard-2-line"></i><span data-key="t-datamaster">Dashboard</span>
                    </a>
                </div>

                {{-- Datamaster menu --}}
                {{-- <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarRecap" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->is('admin/recap*') ? 'true' : 'false' }}"
                        aria-controls="sidebarRecap">
                        <i class="ri-file-excel-2-line"></i><span data-key="t-recap">Recap</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->is('admin/recap*') ? 'show' : '' }}"
                        id="sidebarRecap">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('recap.attendance.index') }}"
                                    class="nav-link {{ request()->routeIs('recap.attendance.index') ? 'active' : '' }}"
                                    data-key="t-recap">
                                    Attendance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('recap.storing.index') }}"
                                    class="nav-link {{ request()->routeIs('recap.storing.index') ? 'active' : '' }}"
                                    data-key="t-recap">
                                    Storing
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a class="nav-link menu-link collapsed" href="#sidebarDashboard" data-bs-toggle="collapse"
                        role="button" aria-expanded="{{ request()->is('admin/datamaster*') ? 'true' : 'false' }}"
                        aria-controls="sidebarDashboard">
                        <i class="ri-database-2-line"></i> <span data-key="t-datamaster">Datamaster</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->is('admin/datamaster*') ? 'show' : '' }}"
                        id="sidebarDashboard">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <!-- User menu item, active if the route is 'datamaster_user' -->
                                <a href="{{ route('datamaster_user.index') }}"
                                    class="nav-link {{ request()->routeIs('datamaster_user.index') ? 'active' : '' }}"
                                    data-key="t-user">
                                    User
                                </a>
                            </li>
                            <li class="nav-item">
                                <!-- User menu item, active if the route is 'datamaster_attendance' -->
                                <a href="{{route('datamaster_attendance.index')}}"
                                    class="nav-link {{ request()->routeIs('datamaster_attendance.index') ? 'active' : '' }}"
                                    data-key="t-attendance">
                                    Attendance
                                </a>
                            </li>
                            <li class="nav-item">
                                <!-- Transporter menu item -->
                                <a href="{{route('datamaster_transporter.index')}}"
                                    class="nav-link {{ request()->is('admin/datamaster/transporter*') ? 'active' : '' }}"
                                    data-key="t-transporter">
                                    Transporter
                                </a>
                            </li>
                            <li class="nav-item">
                                <!-- Storing menu item -->
                                <a href="{{route('datamaster_storing.index')}}"
                                    class="nav-link {{ request()->is('admin/datamaster/storing*') ? 'active' : '' }}"
                                    data-key="t-storing">
                                    Storing
                                </a>
                            </li>
                            <li class="nav-item">
                                <!-- Chronology menu item -->
                                <a href="{{route('datamaster_chronology.index')}}"
                                    class="nav-link {{ request()->is('admin/datamaster/chronology*') ? 'active' : '' }}"
                                    data-key="t-chronology">
                                    Temporary Chronology
                                </a>
                            </li>
                            <li class="nav-item">
                                <!-- Incident menu item -->
                                <a href="{{route('datamaster_incident.index')}}"
                                    class="nav-link {{ request()->is('admin/datamaster/incident*') ? 'active' : '' }}"
                                    data-key="t-incident">
                                    Incident
                                </a>
                            </li>
                            <li class="nav-item">
                                <!-- Investigation menu item -->
                                <a href="{{route('datamaster_investigation.index')}}"
                                    class="nav-link {{ request()->is('admin/datamaster/investigation*') ? 'active' : '' }}"
                                    data-key="t-investigation">
                                    Investigation
                                </a>
                            </li>
                            <li class="nav-item">
                                <!-- vehicle inspection menu item -->
                                <a href="{{route('datamaster_vehicle_inspection.index')}}"
                                    class="nav-link {{ request()->is('admin/datamaster/vehicle-inspection*') ? 'active' : '' }}"
                                    data-key="t-vehicle-inspection">
                                    Vehicle Inspection
                                </a>
                            </li>
                            <li class="nav-item">
                                <!-- vehicle inspection menu item -->
                                <a href="{{route('datamaster_inspection_finding.index')}}"
                                    class="nav-link {{ request()->is('admin/datamaster/inspection-finding*') ? 'active' : '' }}"
                                    data-key="t-inspection-finding">
                                    Inspection Finding
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-other">Other</span>
                </li>
                <div class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('log_activity.index') ? 'active' : '' }}"
                        href="{{route('log_activity.index')}}" aria-expanded="false">
                        <i class="ri-history-fill"></i><span data-key="t-log_activity">Log Activity</span>
                    </a>
                </div> --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
