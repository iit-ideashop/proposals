<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Proposals</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="index.php">Home</a></li>
              <li><a href="login.php">Sign In</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
<?php
//here we will process the flashbangs
include('classes/FlashBang.php');
echo FlashBang::getFlashBang();
?>