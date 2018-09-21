<?php

abstract class AbstractPage {

	public function show(){
		ob_clean();
		ob_start("ob_gzhandler");
		print $this->header();
		print $this->content();
		print $this->footer();
		ob_end_flush();
	}

	public function redir($loc){
		ob_clean();
		header($_SERVER["SERVER_PROTOCOL"]." 302 Found");
		header("Location: ".$loc);
	}

	public abstract function header();
	public abstract function content();
	public abstract function footer();

};
