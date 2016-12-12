<?php
if (!defined ('TYPO3_MODE')) {
	die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output']['robots'] = 'Dschledermann\\Robots\\Hooks\\Tsfe->contentPostProcOutput';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['robots'] = 'Dschledermann\\Robots\\Hooks\\Tcemain';

