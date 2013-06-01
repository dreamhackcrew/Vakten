<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Dreamhack Vakten</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="http://code.jquery.com/ui/jquery-ui-git.js"></script>

    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
    <link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/assets/css/base.css" rel="stylesheet"> 
</head>

<body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">Dreamhack - Vakten</a>
          <div class="nav-collapse collapse">
            <?php if( isset($_SESSION['user']['username']) ) { ?>
                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">Logged in as <strong><?php echo $_SESSION['user']['username']; ?></strong> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/pages/user.php">User information</a></li>
                            <li><a href="/?exit">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            <?php } else { ?>
                <form class="navbar-form pull-right">
                  <a href="/pages/login.php" class="btn">Sign in</a>
                </form>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>


