<body class="skin-black">
<!-- header logo: style can be found in header.less -->
<header class="header">
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
<!-- Sidebar toggle button-->
<?php if($sidebar): ?>
<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</a>
<?php endif; ?>
<!--<a id="navbar-home-btn" href="--><?php //echo SITE_DIR; ?><!--">-->
<!--    <i class="fa fa-home"></i>-->
<!--</a>-->
<div class="navbar-right">
    <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user"></i>
                <span><?php echo registry::get('user')['user_name']; ?> <?php echo registry::get('user')['user_surname']; ?> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                <li class="dropdown-header text-center">Account</li>
                <li class="divider"></li>
                <li>
                    <a data-toggle="modal" href="<?php echo SITE_DIR; ?>settings/">
                        <i class="fa fa-cog fa-fw pull-right"></i>
                        Settings
                    </a>
                </li>

                <li class="divider"></li>

                <li>
                    <a href="#" id="logout_button"><i class="fa fa-ban fa-fw pull-right"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
</nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">