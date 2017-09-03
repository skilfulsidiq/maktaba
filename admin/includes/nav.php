<!-- TOP NAV BAR -->
	<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mynavbar-content">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="index.php" class="navbar-brand">Xploit Admin</a>
                </div>
                    <div class="collapse navbar-collapse" id="mynavbar-content">
                    <ul class="nav navbar-nav">
                                 <!--                    MENU -->
                        <li><a href="brand.php">Brands</a></li>
                        <li>	<a href="categories.php">Categories</a></li>
                        <li>	<a href="product.php">Products</a></li>
                        <li>	<a href="archived.php">Archived</a></li>
                        <?php if(has_permission('admin')):?>
                        <li><a href="users.php">Users</a></li>
                        <?php endif; ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Hello <?php echo $user_data['first'];?>!
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="change_password.php">Change Password</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>


                    </ul>
                </div>
			</div>
		</nav>


        
