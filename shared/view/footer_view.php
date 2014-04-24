<div style="font-size: 8pt; text-align: center; padding-top: 20px;">
<?php
	$timestop = microtime(true);
	print "Server response time: ";
	print round($timestop - $timestart,2);
	print " seconds.";
?>
</div>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="<?php print $site["url"];?>/web/bootstrap/js/bootstrap.js"></script>
    <script src="<?php print $site["url"];?>/web/bootstrap/js/bootstrap-transition.js"></script>
    <script src="<?php print $site["url"];?>/web/bootstrap/js/bootstrap-modal.js"></script>

    <script src="<?php print $site["url"];?>/web/js/data.js"></script>
	<?php echo $view["js"];?>
  </body>
</html>
