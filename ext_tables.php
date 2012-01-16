<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumns = array (
    'tx_aud42cmtscr_mode' => array (        
        'exclude' => 0,        
        'label' => 'Mode',        
        'config' => array (
            'type' => 'select',
            'items' => array (
                array('Submit Comment', '0'),
                array('Edit Comment', '1'),
                array('User Comment List', '2'),
                array('Admin Comment List', '3'),
                array('Auditory Screen', '4'),
            ),
            'size' => 1,    
            'maxitems' => 1,
        )
    ),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);


$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='tx_aud42cmtscr_mode;;;;1-1-1';


$TCA['tx_aud42cmtscr_comments'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:aud42cmtscr/locallang_db.xml:tx_aud42cmtscr_comments',		
		'label'     => 'comment',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate DESC',	
		'delete' => 'deleted',	
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_aud42cmtscr_comments.gif',
	),
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:aud42cmtscr/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/','Lecture Comments');
?>