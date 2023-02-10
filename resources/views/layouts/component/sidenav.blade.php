<div id="layoutSidenav_nav" class="shadow">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading text-primary">Home</div>
                <a class="nav-link" href="{{ route('home') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading text-primary">Manage</div>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePagesOne" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fa fa-users"></i></div>
                    Users
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePagesOne" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="{{ route('users') }}">
                            <i class="fa fa-user"></i>&nbsp;&nbsp;User
                        </a>
                        <a class="nav-link collapsed" href="{{ route('admins') }}">
                            <i class="fa fa-user-secret"></i>&nbsp;&nbsp;Admin
                        </a>
                    </nav>
                </div>
                <a class="nav-link" href="{{ route('locations') }}">
                    <div class="sb-nav-link-icon"><i class="fa fa-map"></i></div>
                    Locations
                </a>
                <a class="nav-link" href="{{ route('attendances') }}">
                    <div class="sb-nav-link-icon"><i class="fa fa-street-view"></i></div>
                    Attendance
                </a>
                <a class="nav-link" href="{{ route('payrolls') }}">
                    <div class="sb-nav-link-icon"><i class="fa fa-file-invoice"></i></div>
                    Payroll
                </a>
                <div class="sb-sidenav-menu-heading text-primary">Options</div>
                <a class="nav-link" href="{{ route('push-messages') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-comment"></i></div>
                    Push Messages
                </a>
                <a class="nav-link" href="{{ route('settings') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-wrench"></i></div>
                    Settings
                </a>
            </div>
        </div>
    </nav>
</div>
