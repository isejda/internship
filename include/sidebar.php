<?php
include 'include/getdata.php';
?>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="inspina/<?php echo isset($_SESSION['picture']) ? $_SESSION['picture'] : ''; ?>" style="width: 80px; height: 80px;" />
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block m-t-xs">
                                    <strong class="font-bold"><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?><?php echo isset($_SESSION['lastname']) ? ' ' . $_SESSION['lastname'] : ''; ?></strong>
                                </span>
                                <!-- Add user role here if available -->
                                <?php if(isset($_SESSION['role'])): ?>
                                    <span class="text-muted text-xs block"><?php echo $_SESSION['role']; ?> <b class="caret"></b></span>
                                <?php endif; ?>
                            </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.php">Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>

            <li>
                <a href="home.php"><i class="fa fa-home" style="font-size:20px"></i><span class="nav-label">Home</span></a>
            </li>


            <?php if($_SESSION['role'] === 'admin'){ ?>
                <li>
                    <a href="dashboard.php"><i class="fa fa-th-large"></i> <span class="nav-label">Dashbord</span></a>
                </li>
            <?php } ?>
        </ul>

    </div>
</nav>