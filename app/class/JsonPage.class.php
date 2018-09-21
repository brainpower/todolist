<?php

class JsonPage extends AbstractPage {
	private $content = "";

	public function setContent($content){
		$this->content = $content;
	}

	public function header() {
	}

	public function content()	{
		print json_encode($this->content);
	}

	public function footer() {
	}
}