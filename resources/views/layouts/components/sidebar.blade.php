<!-- Sidebar -->
<div id="sidebar-container" class="sidebar-collapsed d-none d-md-block">
    <!-- d-* hiddens the Sidebar in smaller devices. Its itens can be kept on the Navbar 'Menu' -->
    <!-- Bootstrap List Group -->
    <ul class="list-group">
        <!-- Separator with title -->
        <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed d-none">
            <small>MAIN MENU</small>
        </li>
        <!-- /END Separator -->
        <!-- Menu with submenu -->
        <a href="#submenu1" data-toggle="collapse" aria-expanded="false"
           class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-start align-items-center">
                <span class="fa fa-dashboard fa-fw mr-3"></span>
                <span class="menu-collapsed d-none">Dashboard</span>
                <span class="submenu-icon d-none ml-auto"></span>
            </div>
        </a>
        <!-- Submenu content -->
        <div id='submenu1' class="collapse sidebar-submenu d-none">
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                <span class="menu-collapsed d-none">Chahgag</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                <span class="menu-collapsed d-none">Reports</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                <span class="menu-collapsed d-none">Tables</span>
            </a>
        </div>
        <a href="#submenu2" data-toggle="collapse" aria-expanded="false"
           class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-start align-items-center">
                <span class="fa fa-user fa-fw mr-3"></span>
                <span class="menu-collapsed d-none">Profile</span>
                <span class="submenu-icon d-none ml-auto"></span>
            </div>
        </a>
        <!-- Submenu content -->
        <div id='submenu2' class="collapse sidebar-submenu d-none">
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                <span class="menu-collapsed d-none">Settings</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                <span class="menu-collapsed d-none">Password</span>
            </a>
        </div>
        <a href="#" class="bg-dark list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-start align-items-center">
                <span class="fa fa-tasks fa-fw mr-3"></span>
                <span class="menu-collapsed d-none">Tasks</span>
            </div>
        </a>
        <!-- Separator with title -->
        <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed d-none">
            <small>OPTIONS</small>
        </li>
        <!-- /END Separator -->
        <a href="#" class="bg-dark list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-start align-items-center">
                <span class="fa fa-calendar fa-fw mr-3"></span>
                <span class="menu-collapsed d-none">Calendar</span>
            </div>
        </a>
        <a href="#" class="bg-dark list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-start align-items-center">
                <span class="fa fa-envelope-o fa-fw mr-3"></span>
                <span class="menu-collapsed d-none">Messages <span
                        class="badge badge-pill badge-primary ml-2">5</span></span>
            </div>
        </a>
        <!-- Separator without title -->
        <li class="list-group-item sidebar-separator menu-collapsed d-none"></li>
        <!-- /END Separator -->
        <a href="#" class="bg-dark list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-start align-items-center">
                <span class="fa fa-question fa-fw mr-3"></span>
                <span class="menu-collapsed d-none">Help</span>
            </div>
        </a>
        <a href="#top" data-toggle="sidebar-colapse"
           class="bg-dark list-group-item list-group-item-action d-flex align-items-center">
            <div class="d-flex w-100 justify-content-start align-items-center">
                <span id="collapse-icon" class="fa fa-2x mr-3"></span>
                <span id="collapse-text" class="menu-collapsed d-none">Collapse</span>
            </div>
        </a>
    </ul><!-- List Group END-->
</div><!-- sidebar-container END -->
