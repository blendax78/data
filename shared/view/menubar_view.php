    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo $site["url"];?>/">Brain Bank</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li <?php echo ($view["title"] == "Home") ? 'class="active"': '';?>><a href="<?php echo $site["url"];?>/">Home</a></li>
              <li <?php echo ($view["title"] == "Groups") ? 'class="active"': '';?>><a href="<?php echo $site["url"];?>/groups/">Groups</a></li>
              <li <?php echo ($view["title"] == "Contacts") ? 'class="active"': '';?>><a href="<?php echo $site["url"];?>/contacts/">Contacts</a></li>
              <li <?php echo ($view["title"] == "Documents") ? 'class="active"': '';?>><a href="<?php echo $site["url"];?>/documents/">Documents</a></li>
              <li <?php echo ($view["title"] == "Events") ? 'class="active"': '';?>><a href="<?php echo $site["url"];?>/events/">Events</a></li>
              <li <?php echo ($view["title"] == "Notes") ? 'class="active"': '';?>><a href="<?php echo $site["url"];?>/notes">Notes</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <?php ?>