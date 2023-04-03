<?php

require_once("app/util/DB.php");
require_once("app/class/Html5Page.class.php");
require_once("app/class/JsonPage.class.php");
require_once("app/class/ListsListComponent.class.php");
require_once("app/class/TodoListComponent.class.php");
require_once("app/class/NewListComponent.class.php");

//ini_set('display_errors', 1);

$dbdbg = function($q, $ps){ echo "<pre style=\"color:grey;\">$q --";      foreach( $ps as $p ){   echo "    '$p'";        }       echo "</pre>\n"; };
//$DB->debug = $dbdbg;

$page = new Html5Page("brainpower's TODO List");

$listID  = $_GET["list"]   ?? null;
$entryID = $_GET["entry"]  ?? null;
$action  = $_GET["action"] ?? null;
$state   = $_GET["state"]  ?? null;

/*
 * No action, no listID -> show index page
 */
if($listID == null && !$action) {
	$list_list = new ListsListComponent( $DB->list, "TODO Lists", "Listing your TODOs since 1846" );
	$page->addComponent($list_list);
}

/*
 * Action: Show List
 * Param:  $listID
 */
elseif( $listID != null && (!$action || $action == "show" )){

	$list = $DB->list[$listID];
	$todoList = new TodoListComponent( $DB->entry->where("list_id = ?", $listID), $list["name"], $list["description"]);
	$page->addComponent($todoList);

}

/*
 * Action: Create new List
 */
elseif($action == "new" && $listId == null){

	$cmp = new NewListComponent();
	$cmp->prepare();
	$page->addComponent($cmp);

}

/*
 * Action: Toggle list item state
 * Param:  $entryID
 * Param:  $state
 */
elseif($action == "update" && $entryID !== null){

	$out = array();
	$page = new JsonPage();
	//$DB->debug = function($q, $ps) use (&$out) { $out["dbdbg"] .= "$q --"; foreach( $ps as $p ){ $out["dbdbg"] .= "'$p'";} };;

	if($state !== null){
		$updated = $DB->entry[$entryID]->update(array(
			"state" => $state,
			"updated_at" => (new DateTimeImmutable())->format('Y-m-d H:i:s')
		));
		if ($updated === 0 && intval($DBH->errorCode()) !== 0) {
			$out["status"] = "error";
			$out["reason"] = "error updating item database entry";
		} else {
			$listID = $DB->entry[$entryID]["list_id"];
			$finishedCount = $DB->entry->where("list_id = ?", $listID)->and("state > 0")->count("*");
			$unfinishedCount = $DB->entry->where("list_id = ?", $listID)->and("state = 0")->count("*");
			$res = -1;
			if( $unfinishedCount > 0 ){
				if( $finishedCount > 0 ){
					$res = $DB->list[$listID]->update(array("status" => 1));
				} else {
					$res = $DB->list[$listID]->update(array("status" => 0));
				}
			} else {
				$res = $DB->list[$listID]->update(array("status" => 2)) < 1;
			}
			if( $res === 0 && intval($DBH->errorCode()) !== 0) {
				$out["status"] = "error";
				$out["reason"] = "error updating list database entry ({$DBH->errorCode()}: {$DBH->errorInfo()[2]} / {$finishedCount} / {$unfinishedCount} / {$listID})";
			} else {
				$out["status"] = "success";
			}
		}
	}
	//elseif(isset($_GET["subaction"])) {		}

	$page->setContent($out);

	//$DB->debug = $dbdbg;
}

/*
 * Action: Delete list
 * Param:  $listID
 */
elseif($action == "delete" && $listID !== null) {

	$DB->entry("list_id = ?", $listID)->delete();
	if ($DB->list("id = ?", $listID)->delete()) {
		$page->redir(ROOT_URL); // success
	} else {
		die('ERROR deleting list failed');
	}

}

/*
 * Action: Edit List
 * Param:  $listID
 */
elseif($action == "edit" && $listID !== null) {

	$list = $DB->list[$listID];
	$cmp = new NewListComponent($action, $list);
	$cmp->prepare();
	$page->addComponent($cmp);

} else {
	die("Invalid request!");
}


#$page->addContent("Main Page");

$page->show();


