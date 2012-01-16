<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_aud42cmtscr_comments'] = array (
	'ctrl' => $TCA['tx_aud42cmtscr_comments']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'comment,fe_user_uid,status'
	),
	'feInterface' => $TCA['tx_aud42cmtscr_comments']['feInterface'],
	'columns' => array (
		'comment' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:aud42cmtscr/locallang_db.xml:tx_aud42cmtscr_comments.comment',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'fe_user_uid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:aud42cmtscr/locallang_db.xml:tx_aud42cmtscr_comments.fe_user_uid',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'fe_users',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'status' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:aud42cmtscr/locallang_db.xml:tx_aud42cmtscr_comments.status',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:aud42cmtscr/locallang_db.xml:tx_aud42cmtscr_comments.status.I.0', '0'),
					array('LLL:EXT:aud42cmtscr/locallang_db.xml:tx_aud42cmtscr_comments.status.I.1', '1'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'comment;;;;1-1-1, fe_user_uid, status')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>