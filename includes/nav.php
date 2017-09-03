<!-- TOP NAV BAR -->
<?php
$sql = "SELECT * FROM categories WHERE parent = 0";
$query = $conn->query($sql);

?>
		<nav class="navbar navbar-inverse navbar-fixed-top" id="nav">
			<div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mynavbar-content">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="index.php" class="navbar-brand">Xploit Store</a>
                </div>
                    <div class="collapse navbar-collapse" id="mynavbar-content">
                    <ul class="nav navbar-nav">
                        <?php while($row = mysqli_fetch_assoc($query)) :?>
                        <?php
                            $parent = $row['id'];
                            $sql2 ="SELECT * FROM categories WHERE parent = '$parent'";
                            $query2 = $conn->query($sql2);

                        ?>
                                 <!--                    MENU -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $row['category']; ?><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php while($child = mysqli_fetch_assoc($query2)) : ?>
                                <li><a href="category.php?cat=<?=$child['id'];?>"><?php echo $child['category'];?></a></li>
                                <?php endwhile;?>

                            </ul>
                        </li>
                        <?php endwhile; ?>
												<li><a href="admin/index.php">Admin</a></li>
                    </ul>
                </div>
			</div>
		</nav>
