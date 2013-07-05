<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "tinymce_rte".
 *
 * Auto generated 05-07-2013 16:54
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'TinyMCE RTE',
	'description' => 'An advanced integration of TinyMCE which supports all TinyMCE plugins and languages.',
	'category' => 'be',
	'shy' => 0,
	'version' => '0.9.2',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1,mod2,mod3,mod4',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => 'typo3temp/tinymce_rte/',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Thomas Allmer',
	'author_email' => 'd4kmor@gmail.com',
	'author_company' => '',
	'CGLcompliance' => NULL,
	'CGLcompliance_note' => NULL,
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '6.0.0-6.2.0',
		),
		'conflicts' => '',
		'suggests' => 
		array (
		),
	),
);

?>