<?php

require_once("app/class/ListComponent.class.php");

class ListsListComponent extends ListComponent {
	public function __construct($data, $title=null){
		parent::__construct($data, $title);
	}

	protected function showEntry($entry_data){
		$status = "unstarted";
		if($entry_data["status"] == 1){
			$status = "unfinished";
		} elseif($entry_data["status"] == 2){
			$status = "finished";
		}
		$title = htmlspecialchars($entry_data["name"]);
		$descr = htmlspecialchars($entry_data["description"]);
		print <<<"EOF"
				<div class="list-row {$status}">
					<a href="?list={$entry_data["id"]}">
						<div class="list-col title">{$title}</div>
						<div class="list-col description">{$descr}</div>
					</a>
					<div class="list-col right">
						<a class="button" href="?action=edit&list={$entry_data["id"]}">E</a><a class="button button-red" href="?action=delete&list={$entry_data["id"]}">D</a>
					</div>
				</div>
EOF;
	}

	protected function header(){
		print '<div class="actions">';
		print '<a class="list-new button" href="?action=new">New List</a>';
		print '</div>';
	}
};
