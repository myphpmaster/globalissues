    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; myPHPmaster 2014</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                    <?php foreach($site_socials as $service => $link): ?>
                        <li><a href="<?php echo $link ?>" target="_blank"><i class="fa fa-<?php echo $service ?>"></i></a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="https://koding.com/Hackathon" target="_blank">Koding Global Hackathon</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

<!-- Lightbox script - https://github.com/ashleydw/lightbox -->
<script type="text/javascript">
$(document).ready(function ($) {
	// delegate calls to data-toggle="lightbox"
	$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
		event.preventDefault();
		return $(this).ekkoLightbox({
			always_show_close: true
		});
	});

});
</script>

  </body>
</html>