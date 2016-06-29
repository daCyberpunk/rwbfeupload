<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RWB.' . $_EXTKEY,
	'Rwbfeupload',
	array(
		'Main' => 'list, show, edit, new, create, delete, update',
		
	),
	// non-cacheable actions
	array(
		'Main' => 'edit, new, create, delete, update',
		
	)
);
