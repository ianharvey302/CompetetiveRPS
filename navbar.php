<nav class="navbar navbar-expand">
    <a class="navbar-brand" href="?command=home">
        Competitive RPS
    </a>
    <ul class="navbar-nav me-auto">
        <li class="vr"></li>
        <li class="nav-item">
            <a href="?command=play" class="btn btn-lg">Play!</a>
        </li>
    </ul>
    <ul class="nav navbar-nav ms-auto">
        <li class="nav-item">
            <!--For the account pfp-->
            <a class="nav-link" href="<?php if(isset($_SESSION['username'])) {echo '?command=profile';} else {echo '?command=signup';}?>"  id="ProfileIcon">
                <span id="Profile-Text">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo $_SESSION["username"];
                    }
                    else {
                        echo "Profile";
                    }
                    ?>
                </span>
                <img class="rounded"  src="images/DefaultProfile.png" alt="The user's profile picture">
            </a>
        </li>
    </ul>
</nav>