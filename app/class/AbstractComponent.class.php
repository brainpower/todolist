<?php

abstract class AbstractComponent {
	protected $page;

	abstract public function show();
	public function prepare(){}
	public function setPage($p){ $this->page = $p; }

	public function scripts(){ return array(); }
	public function styles() { return array(); }
};
