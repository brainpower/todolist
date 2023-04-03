<?php

require_once("app/util/DB.php");
require_once("app/class/AbstractComponent.class.php");

class NewListComponent extends AbstractComponent {
	private $action;
	private $list;
	private $new_list_added = false;

	public function __construct($action = "new", $listID = null){
		global $DB;
		$this->list = $listID ? $DB->list[$listID] : null;
		$this->action = $action;
	}

	public function prepare(){
		global $DB;
		$now = new DateTimeImmutable();
		$nowStr = $now->format('Y-m-d H:i:s');
		if(isset($_POST["name"])){
			if ($this->action === "new") {
				$new_list = array(
					"name" => $_POST["name"],
					"created_at" => $nowStr,
					"updated_at" => $nowStr
				);
				if (isset($_POST["description"])) {
					$new_list["description"] = $_POST["description"];
				}
				$DB->list->insert($new_list);
				$this->new_list_added = $DB->list->insert_id();
				if (isset($_POST["rawlist"]) && !empty($_POST["rawlist"])) {
					foreach (preg_split("/\r\n|\n|\r/", $_POST["rawlist"]) as $line) {
						if ($line !== "") {
							$DB->entry->insert(array(
								"name" => $line,
								"list_id" => $this->new_list_added,
								"state" => 0,
								"created_at" => $nowStr,
								"updated_at" => $nowStr
							));
						}
					}
				}
			} elseif ($this->action === "edit") {
				$listID = $this->list["id"];
				$this->list->update(array(
					"name" => $_POST["name"],
					"updated_at" => $nowStr
				));
				if (isset($_POST["rawlist"]) && !empty($_POST["rawlist"])) {
					$DB->entry('list_id = ?', $listID)->delete();
					foreach (preg_split("/\r\n|\n|\r/", $_POST["rawlist"]) as $line) {
						if ($line !== "") {
							$DB->entry->insert(array(
								"name" => $line,
								"list_id" => $listID,
								"state" => 0,
								"created_at" => $nowStr,
								"updated_at" => $nowStr
							));
						}
					}
				}
				$this->new_list_added = $listID;
			}
		}

	}

	public function show() {
		global $DB;
		if(!$this->new_list_added){
			$header = $this->action === "edit" ? "Edit List" : "New List";
			$action = $this->action === "edit" ? "{$this->action}&list={$this->list["id"]}" : "new";
			$title = $this->list ? $this->list["name"] : '';
			$descr = $this->list ? $this->list["description"] : '';
			$entries = array();

			if ($this->list) {
				foreach ($DB->entry('list_id = ?', $this->list["id"]) as $item) {
					$entries[] = "{$item["name"]}";
				}
				$entries = implode("\n", $entries);
			} else {
				$entries = '';
			}

			print <<<"EOF"
<div class="form">
	<form action="?action={$action}" method="post">
		<div class="form-title">{$header}</div>
		<input name="name" type="text" placeholder="Name..." value="{$title}"><br>
		<input name="description" type="text" placeholder="Description..." value="{$descr}"><br>
		<label for="rawlist" title="Newline-separated plain text list">Import list entries from text below:</label><br>
		<textarea name="rawlist" title="Newline-separated plain text list">{$entries}</textarea><br>
		<input class="button" type="submit" value="Go!">
	</form>
</div>
EOF;
		}
		else {
			$this->page->redir("?list=" . $this->new_list_added );
		}
	}
};
