<?php

require_once("app/class/AbstractComponent.class.php");

class ListComponent extends AbstractComponent {
	private $data;
	private $title;
	private $description;

	public function __construct($data, $title=null, $description=null){
		$this->data = $data;
		$this->title = $title;
		$this->description = $description;
	}
	public function show(){
		print '<div class="list">'."\n";
		if($this->title){
			print '<div class="list-title">'. htmlspecialchars($this->title) . "</div>\n";
		}
		if($this->description){
			print '<div class="list-description">' . htmlspecialchars($this->description) . '</div>';
		}

		$this->header();
		print '<div class="list-entries">';
		foreach ($this->data as $value) {
			$this->showEntry($value);
		}
		print	"</div>\n";

		$this->footer();
		print "</div>\n";
	}

	protected function header(){
		$backlink = ROOT_URL;
		print <<<EOF
		<div class="nav-back">
			<a href="{$backlink}" class="button">Back</a>
		</div>
EOF;
	}
	protected function footer(){ }

	public function scripts(){ return array('js/toggle.js'); }


	protected function showEntry($entry_data){
		print $entry_data;
	}

};
