<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'RWB.' . $_EXTKEY,
	'Rwbfeupload',
	'RWB FE Upload Example'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'RWB Example Usage for EXT media_upload (FE file upload)');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rwbfeupload_domain_model_main', 'EXT:rwbfeupload/Resources/Private/Language/locallang_csh_tx_rwbfeupload_domain_model_main.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rwbfeupload_domain_model_main');

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('

	# Default pid for "fe_groups" in Vidi:
	tx_vidi.dataType.fe_groups.storagePid = 2
');