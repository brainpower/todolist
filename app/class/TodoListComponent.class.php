<?php


require_once("app/class/ListComponent.class.php");

class TodoListComponent extends ListComponent {
	public function __construct($data, $title, $description){
		parent::__construct($data, $title, $description);
	}

	protected function showEntry($entry_data){
		$state = "unstarted";
		if( intval($entry_data["state"]) > 0 ){
			$state = "finished";
		}
		$entry = htmlspecialchars($entry_data["name"]);

		print <<<"EOF"
			<div class="list-row only {$state}" data-id="{$entry_data["id"]}">
				{$entry}
			</div>
EOF;
		// 				<div class="list-col right">
		//					<a class="button" data-id="{$entry_data["id"]}">E</a><a class="button button-red" data-id="{$entry_data["id"]}">D</a>
		//				</div>
	}
};
