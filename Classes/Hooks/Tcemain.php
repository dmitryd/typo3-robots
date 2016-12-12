<?php

namespace Dschledermann\Robots\Hooks;


class Tcemain {

	function processDatamap_afterDatabaseOperations($status, $table, $id, &$fieldArray, &$pObj) {
		if($table == 'pages'  && isset($fieldArray['tx_robots_flags']) && $status == 'update' ){
			if($fieldArray['tx_robots_flags'] & 4){
				$updateArray = array('tx_robots_flags' => $fieldArray['tx_robots_flags']);
			} else {
				$updateArray = array('tx_robots_flags' => 0);
			}
			$this->updateChilds($id,$updateArray);
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
