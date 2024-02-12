<?php

namespace RRZE\FAQ\Config;

defined('ABSPATH') || exit;

/**
 * Gibt der Name der Option zurück.
 * @return array [description]
 */
function getOptionName() {
    return 'rrze-lexikon';
}

function getConstants() {
	$options = array(
		'fauthemes' => [
			'FAU-Einrichtungen',
			'FAU-Einrichtungen-BETA',
			'FAU-Medfak',
			'FAU-RWFak',
			'FAU-Philfak',
			'FAU-Techfak',
			'FAU-Natfak',
			'FAU-Blog',
			'FAU-Jobs'
		],
		'rrzethemes' => [
			'RRZE 2019',
		],
		'langcodes' => [
			"de" => __('German','rrze-synonym'),
			"en" => __('English','rrze-synonym'),
			"es" => __('Spanish','rrze-synonym'),
			"fr" => __('French','rrze-synonym'),
			"ru" => __('Russian','rrze-synonym'),
			"zh" => __('Chinese','rrze-synonym')
		]
	);               
	return $options;
}


/**
 * Gibt die Einstellungen des Menus zurück.
 * @return array [description]
 */
function getMenuSettings() {
    return [
        'page_title'    => __('RRZE FAQ', 'rrze-lexikon'),
        'menu_title'    => __('RRZE FAQ', 'rrze-lexikon'),
        'capability'    => 'manage_options',
        'menu_slug'     => 'rrze-lexikon',
        'title'         => __('RRZE FAQ Settings', 'rrze-lexikon'),
    ];
}

/**
 * Gibt die Einstellungen der Inhaltshilfe zurück.
 * @return array [description]
 */
function getHelpTab() {
    return [
        [
            'id'        => 'rrze-lexikon-help',
            'content'   => [
                '<p>' . __('Here comes the Context Help content.', 'rrze-lexikon') . '</p>'
            ],
            'title'     => __('Overview', 'rrze-lexikon'),
            'sidebar'   => sprintf('<p><strong>%1$s:</strong></p><p><a href="https://blogs.fau.de/webworking">RRZE Webworking</a></p><p><a href="https://github.com/RRZE Webteam">%2$s</a></p>', __('For more information', 'rrze-lexikon'), __('RRZE Webteam on Github', 'rrze-lexikon'))
        ]
    ];
}

/**
 * Gibt die Einstellungen der Optionsbereiche zurück.
 * @return array [description]
 */

function getSections() {
	return [ 
		[
			'id'    => 'doms',
			'title' => __('Domains', 'rrze-lexikon' )
		],
		[
			'id'    => 'faqsync',
			'title' => __('Synchronize', 'rrze-lexikon' )
		],
		[
		  	'id' => 'faqlog',
		  	'title' => __('Logfile', 'rrze-lexikon' )
		]
	];   
}

/**
 * Gibt die Einstellungen der Optionsfelder zurück.
 * @return array [description]
 */

function getFields() {
	return [
		'doms' => [
			[
				'name' => 'new_name',
				'label' => __('Short name', 'rrze-lexikon' ),
				'desc' => __('Enter a short name for this domain.', 'rrze-lexikon' ),
				'type' => 'text'
			],
			[
				'name' => 'new_url',
				'label' => __('URL', 'rrze-lexikon' ),
				'desc' => __('Enter the domain\'s URL you want to receive FAQ from.', 'rrze-lexikon' ),
				'type' => 'text'
			]
		],
		'faqsync' => [
			[
				'name' => 'shortname',
				'label' => __('Short name', 'rrze-lexikon' ),
				'desc' => __('Use this name as attribute \'domain\' in shortcode [faq]', 'rrze-lexikon' ),
				'type' => 'plaintext',
				'default' => ''
			],
			[
				'name' => 'url',
				'label' => __('URL', 'rrze-lexikon' ),
				'desc' => '',
				'type' => 'plaintext',
				'default' => ''
			],
			[
				'name' => 'categories',
				'label' => __('Categories', 'rrze-lexikon' ),
				'desc' => __('Please select the categories you\'d like to fetch FAQ to.', 'rrze-lexikon' ),
				'type' => 'multiselect',
				'options' => []
			],
			[
				'name' => 'donotsync',
				'label' => __('Synchronize', 'rrze-lexikon' ),
				'desc' => __('Do not synchronize', 'rrze-lexikon' ),
				'type' => 'checkbox',
			],
			[
				'name' => 'hr',
				'label' => '',
				'desc' => '',
				'type' => 'line'
			],
			[
				'name' => 'info',
				'label' => __('Info', 'rrze-lexikon' ),
				'desc' => __( 'All FAQ that match to the selected categories will be updated or inserted. Already synchronized FAQ that refer to categories which are not selected will be deleted. FAQ that have been deleted at the remote website will be deleted on this website, too.', 'rrze-lexikon' ),
				'type' => 'plaintext',
				'default' => __( 'All FAQ that match to the selected categories will be updated or inserted. Already synchronized FAQ that refer to categories which are not selected will be deleted. FAQ that have been deleted at the remote website will be deleted on this website, too.', 'rrze-lexikon' ),
			],
			[
				'name' => 'autosync',
				'label' => __('Mode', 'rrze-lexikon' ),
				'desc' => __('Synchronize automatically', 'rrze-lexikon' ),
				'type' => 'checkbox',
			],
			[
				'name' => 'frequency',
				'label' => __('Frequency', 'rrze-lexikon' ),
				'desc' => '',
				'default' => 'daily',
				'options' => [
					'daily' => __('daily', 'rrze-lexikon' ),
					'twicedaily' => __('twicedaily', 'rrze-lexikon' )
				],
				'type' => 'select'
			],
		],		
    	'faqlog' => [
        	[
          		'name' => 'faqlogfile',
          		'type' => 'logfile',
          		'default' => FAQLOGFILE
        	]
      	]
	];
}


/**
 * Gibt die Einstellungen der Parameter für Shortcode für den klassischen Editor und für Gutenberg zurück.
 * @return array [description]
 */

function getShortcodeSettings(){
	$conts = getConstants();
	$langs = $conts['langcodes'];
	
	$ret = [
		'block' => [
            'blocktype' => 'rrze-lexikon/faq',
			'blockname' => 'faq',
			'title' => 'RRZE FAQ',
			'category' => 'widgets',
            'icon' => 'editor-help',
            'tinymce_icon' => 'help'
		],
        'glossary' => [
			'values' => [
                [
                    'id' => '',
                    'val' => __( 'none', 'rrze-lexikon' )
                ],
                [
                    'id' => 'category',
                    'val' => __( 'Categories', 'rrze-lexikon' )
                ],
                [
                    'id' => 'tag',
                    'val' => __( 'Tags', 'rrze-lexikon' )
                ]			
            ],
			'default' => '',
			'field_type' => 'select',
			'label' => __( 'Glossary content', 'rrze-lexikon' ),
			'type' => 'string'
		],
        'glossarystyle' => [
			'values' => [
                [
                    'id' => '',
                    'val' => __( '-- hidden --', 'rrze-lexikon' )
                ],
                [
                    'id' => 'a-z',
                    'val' => __( 'A - Z', 'rrze-lexikon' )
                ],
                [
                    'id' => 'tagcloud',
                    'val' => __( 'Tagcloud', 'rrze-lexikon' )
                ],			
                [
                    'id' => 'tabs',
                    'val' => __( 'Tabs', 'rrze-lexikon' )
                ]
            ],
			'default' => 'a-z',
			'field_type' => 'select',
			'label' => __( 'Glossary style', 'rrze-lexikon' ),
			'type' => 'string'
		],
		'category' => [
			'default' => '',
			'field_type' => 'text',
			'label' => __( 'Categories', 'rrze-lexikon' ),
			'type' => 'text'
        ],
		'tag' => [
			'default' => '',
			'field_type' => 'text',
			'label' => __( 'Tags', 'rrze-lexikon' ),
			'type' => 'text'
        ],
		'id' => [
			'default' => NULL,
			'field_type' => 'text',
			'label' => __( 'FAQ', 'rrze-lexikon' ),
			'type' => 'number'
		],
		'hide_accordion' => [
			'field_type' => 'toggle',
			'label' => __( 'Hide accordeon', 'rrze-lexikon' ),
			'type' => 'boolean',
			'default' => FALSE,
			'checked'   => FALSE
		],	  
		'hide_title' => [
			'field_type' => 'toggle',
			'label' => __( 'Hide title', 'rrze-lexikon' ),
			'type' => 'boolean',
			'default' => FALSE,
			'checked'   => FALSE
		],	  
		'expand_all_link' => [
			'field_type' => 'toggle',
			'label' => __( 'Show "expand all" button', 'rrze-lexikon' ),
			'type' => 'boolean',
			'default' => FALSE,
			'checked'   => FALSE
		],	  
		'load_open' => [
			'field_type' => 'toggle',
			'label' => __( 'Load website with opened accordeons', 'rrze-lexikon' ),
			'type' => 'boolean',
			'default' => FALSE,
			'checked'   => FALSE
		],	  
		'color' => [
			'values' => [
                [
                    'id' => 'fau',
                    'val' => 'fau'
                ],
                [
                    'id' => 'med',
                    'val' => 'med'
                ],
                [
                    'id' => 'nat',
                    'val' => 'nat'
                ],
                [
                    'id' => 'phil',
                    'val' => 'phil'
                ],
                [
                    'id' => 'rw',
                    'val' => 'rw'
                ],
                [
                    'id' => 'tf',
                    'val' => 'tf'
                ],
			],
			'default' => 'fau',
			'field_type' => 'select',
			'label' => __( 'Color', 'rrze-lexikon' ),
			'type' => 'string'
		],
		'additional_class' => [
			'default' => '',
			'field_type' => 'text',
			'label' => __( 'Additonal CSS-class(es) for sourrounding DIV', 'rrze-lexikon' ),
			'type' => 'text'
		],
        'lang' => [
			'default' => '',
			'field_type' => 'select',
			'label' => __( 'Language', 'rrze-lexikon' ),
			'type' => 'string'
		],
        'sort' => [
			'values' => [
                [
                    'id' => 'title',
                    'val' => __( 'Title', 'rrze-lexikon' )
                ],
                [
                    'id' => 'id',
                    'val' => __( 'ID', 'rrze-lexikon' )
                ],
                [
                    'id' => 'sortfield',
                    'val' => __( 'Sort field', 'rrze-lexikon' )
                ],
			],
			'default' => 'title',
			'field_type' => 'select',
			'label' => __( 'Sort', 'rrze-lexikon' ),
			'type' => 'string'
		],
        'order' => [
			'values' => [
                [
                    'id' => 'ASC',
                    'val' => __( 'ASC', 'rrze-lexikon' )
                ],
                [
                    'id' => 'DESC',
                    'val' => __( 'DESC', 'rrze-lexikon' )
                ],
			],
			'default' => 'ASC',
			'field_type' => 'select',
			'label' => __( 'Order', 'rrze-lexikon' ),
			'type' => 'string'
		],
		'hstart' => [
			'default' => 2,
			'field_type' => 'text',
			'label' => __( 'Heading level of the first heading', 'rrze-lexikon' ),
			'type' => 'number' 
		],
    ];

	$ret['lang']['values'] = [
		[
			'id' => '',
			'val' => __( 'All languages', 'rrze-lexikon')
		],
	];
	$consts = getConstants();
	$langs = $consts['langcodes'];
	asort($langs);

	foreach($langs as $short => $long){
		$ret['lang']['values'][] = 
			[
				'id' => $short,
				'val' => $long
			];
	}

	return $ret;

}

function logIt( $msg ){
	date_default_timezone_set('Europe/Berlin');
	$msg = date("Y-m-d H:i:s") . ' | ' . $msg;
	if ( file_exists( FAQLOGFILE ) ){
		$content = file_get_contents( FAQLOGFILE );
		$content = $msg . "\n" . $content;
	}else {
		$content = $msg;
	}
	file_put_contents( FAQLOGFILE, $content, LOCK_EX);
}
  
function deleteLogfile(){
	unlink( FAQLOGFILE );
}
  

