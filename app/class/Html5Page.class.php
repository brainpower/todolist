<?php

require_once("app/class/AbstractPage.class.php");
require_once("app/class/AbstractComponent.class.php");

class Html5Page extends AbstractPage {
	private $styles;
	private $scripts;
	private $title = "";
	private $content = "";
	private $components = array();

	public function __construct($title){
		$this->styles = array(
			"css/style.css",
			//"css/3rdparty/bootstrap.min.css",
		);

		$this->scripts = array(
			"js/3rdparty/jquery.min.js",
			//"js/3rdparty/bootstrap.min.js"
		);

		$this->title = $title;
	}

	public function addStyle($cssfile){
		$this->styles[] = $cssfile;
	}
	public function addScript($jsfile){
		$this->scripts[] = $jsfile;
	}

	protected function setContent($content){
		$this->content = $content;
	}
	public function addContent($content){
		$this->content .= $content;
	}

	public function addComponent(AbstractComponent $c){
		$c->setPage($this);
		$this->components[] = $c;
		foreach ($c->scripts() as $script) {
			$this->addScript($script);
		}
		foreach ($c->styles() as $styles) {
			$this->addStyle($styles);
		}
	}

	public function header(){
		print "<!DOCTYPE html>\n";
		print "<html>\n";
		print "  <head>\n";
		print "    <title>{$this->title}</title>\n";
		print '    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\n";
		foreach ($this->styles as $style) {
			print '    <link rel="stylesheet" type="text/css" href="' . $style . "\">\n";
		}
		print "  </head>\n";

	}

	public function content(){
		print "<body>\n";
		foreach ($this->components as $component) {
			$component->show();
		}
		print $this->content;
	}

	public function footer(){
		foreach ($this->scripts as $script) {
			print '<script type="text/javascript" src="' . $script . '"></script>';
		}
		print "</body>\n";
	}

}
