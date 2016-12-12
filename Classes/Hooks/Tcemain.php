<?php

namespace Dschledermann\Robots\Hooks;


class Tcemain {

	function processDatamap_afterDatabaseOperations($status, $table, $id, &$fieldArray, &$pObj) {
		// If a page is updated and the robot flags are on
		if ($table == 'pages' && isset($fieldArray['tx_robots_flags']) && $status == 'update' ) {

			// Apply to all subpages
			if($fieldArray['tx_robots_flags'] & 4){
				$updateArray = array('tx_robots_flags' => $fieldArray['tx_robots_flags']);
			} else {
				$updateArray = array('tx_robots_flags' => 0);
			}
			$this->updateChilds($id,$updateArray);
		}
	}

	public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, &$parentObj) {

		// A new page should inherit a parent page's setting if the parents inherit checkbox is marked
		if ($table == 'pages' && stripos($id, 'new') !== false) {
			global $TYPO3_DB;

			$rs = $TYPO3_DB->exec_SELECTquery('tx_robots_flags', 'pages', 'uid = ' . $incomingFieldArray['pid']);
			list($robots_flags) = $TYPO3_DB->sql_fetch_row($rs);

			if ($robots_flags & 4) {
				$incomingFieldArray['tx_robots_flags'] = $robots_flags;
			}
		}
	}

	function updateChilds($pid, $updateArray){
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages','pid='.intval($pid),$updateArray);
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,pid','pages','pid='.intval($pid));
		if(count($rows[0])>1){
			foreach($rows as $row){
				$this->updateChilds($row['uid'],$updateArray);
			}
		}
	}
}
