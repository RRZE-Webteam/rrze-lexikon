<?php

namespace RRZE\FAQ;

defined('ABSPATH') || exit;

/**
 * Custom Post Type "faq"
 */
class CPT
{
    private $lang = '';

    public function __construct()
    {
        $this->lang = substr(get_locale(), 0, 2);
        add_action('init', [$this, 'registerCPT'], 0);
        add_action('init', [$this, 'registerTaxonomy'], 0);

        foreach (CPT as $cpt => $aDetails) {
            add_action('publish_' . $cpt, [$this, 'setPostMeta'], 10, 1);

            foreach ($aDetails['taxonomy'] as $aTaxonomy) {
                add_action('create_' . $aTaxonomy['taxonomy'], [$this, 'setTermMeta'], 10, 1);
            }
        }
        add_filter('single_template', [$this, 'filter_single_template']);
        add_filter('archive_template', [$this, 'filter_archive_template']);
        add_filter('taxonomy_template', [$this, 'filter_taxonomy_template']);
    }


    public function registerCPT()
    {
        foreach (CPT as $cpt => $aDetails) {

            $labels = array(
                'name' => $aDetails['singular'],
                'singular_name' => $aDetails['singular'],
                __('Single', 'rrze-lexikon') . ' ' . $aDetails['singular'],
                'menu_name' => $aDetails['singular'],
                'add_new' => __('Add', 'rrze-lexikon') . ' ' . $aDetails['singular'],
                'add_new_item' => __('Add new', 'rrze-lexikon') . ' ' . $aDetails['singular'],
                'edit_item' => __('Edit', 'rrze-lexikon') . ' ' . $aDetails['singular'],
                'all_items' => __('All', 'rrze-lexikon') . ' ' . $aDetails['plural'],
                'search_items' => __('Search', 'rrze-lexikon') . ' ' . $aDetails['plural'],
            );
            $rewrite = array(
                'slug' => $cpt,
                'with_front' => true,
                'pages' => true,
                'feeds' => true,
            );
            $args = array(
                'label' => $aDetails['singular'],
                'description' => $aDetails['singular'] . ' ' . __('informations', 'rrze-lexikon'),
                'labels' => $labels,
                'supports' => ['title', 'editor'],
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => true,
                'menu_icon' => $aDetails['menu_icon'],
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'query_var' => $cpt,
                'rewrite' => $rewrite,
                'show_in_rest' => true,
                'rest_base' => $cpt,
                'rest_controller_class' => 'WP_REST_Posts_Controller',
            );
            register_post_type($cpt, $args);
        }
    }

    public function registerTaxonomy()
    {
        foreach (CPT as $cpt => $aDetails) {
            foreach ($aDetails['aTaxonomy'] as $aTaxonomy) {
                $aLabels = [
                    'singular_name' => $aTaxonomy['singular'],
                    __('Single', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'add_new' => __('Add', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'add_new_item' => __('Add', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'new_item' => __('New', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'view_item' => __('Show', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'view_items' => __('New', 'rrze-lexikon') . ' ' . $aTaxonomy['plural'],
                    'not_found' => $aTaxonomy['singular'] . __('not found', 'rrze-lexikon'),
                    'all_item' => __('All', 'rrze-lexikon') . ' ' . $aTaxonomy['plural'],
                    'separate_items_with_commas' => __('New', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'new_item' => __('New', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'new_item' => __('New', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'new_item' => __('New', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'new_item' => __('New', 'rrze-lexikon') . ' ' . $aTaxonomy['singular'],
                    'separate_items_with_commas' => __('Separate', 'rrze-lexikon') . ' ' . $aTaxonomy['plural'] . ' ' . __('with commas', 'rrze-lexikon'),
                    'choose_from_most_used' => __('Choose from the most used', 'rrze-lexikon') . ' ' . $aTaxonomy['plural'],
                    'separate_items_with_commas' => __('Separate', 'rrze-lexikon') . ' ' . $aTaxonomy['plural'] . ' ' . __('with commas', 'rrze-lexikon'),
                    'edit_item' => __('Edit', 'rrze-lexikon') . ' ' . $aTaxonomy['plural'],
                    'update_item' => __('Update', 'rrze-lexikon') . ' ' . $aTaxonomy['plural'],
                ];

                register_taxonomy(
                    $aTaxonomy['taxonomy'],  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
                    $cpt,   		 //post type name
                    array(
                        'hierarchical' => $aTaxonomy['hierarchical'],
                        'label' => $aTaxonomy['singular'], //Display name
                        'labels' => $aLabels,
                        'show_ui' => TRUE,
                        'show_admin_column' => TRUE,
                        'query_var' => TRUE,
                        'rewrite' => array(
                            'slug' => $aTaxonomy['taxonomy'], // This controls the base slug that will display before each term
                            'with_front' => TRUE // Don't display the category base before
                        ),
                        'show_in_rest' => TRUE,
                        'rest_base' => $t['rest_base'],
                        'rest_controller_class' => 'WP_REST_Terms_Controller'
                    )
                );

                foreach($aTaxonomy['term'] as $term){
                    register_term_meta(
                        $aTaxonomy['taxonomy'],
                        $term,
                        array(
                            'query_var' => TRUE,
                            'type' => 'string',
                            'single' => TRUE,
                            'show_in_rest' => TRUE,
                            'rest_base' => 'source',
                            'rest_controller_class' => 'WP_REST_Terms_Controller'
                        )
                    );
        
                }

            }

        }
    }

    public function setPostMeta($postID)
    {
        add_post_meta($postID, 'source', 'website', TRUE);
        add_post_meta($postID, 'lang', $this->lang, TRUE);
        add_post_meta($postID, 'remoteID', $postID, TRUE);
        $remoteChanged = get_post_timestamp($postID, 'modified');
        add_post_meta($postID, 'remoteChanged', $remoteChanged, TRUE);
    }

    public function setTermMeta($termID)
    {
        add_term_meta($termID, 'source', 'website', TRUE);
        add_term_meta($termID, 'lang', $this->lang, TRUE);
    }


    public function filter_single_template($template)
    {
        global $post;
        if (in_array($post->post_type, array_keys(CPT))) {
            $template = plugin_dir_path(__DIR__) . 'templates/single-' . $post->post_type . '.php';
        }
        return $template;
    }

    public function filter_archive_template($template)
    {
        global $post;
        if (is_post_type_archive(array_keys(CPT))) {
            $template = plugin_dir_path(__DIR__) . 'templates/archive-' . $post->post_type . '.php';
        }
        return $template;
    }

    public function filter_taxonomy_template($template)
    {
        foreach (CPT as $cpt => $aDetails) {
            foreach ($aDetails['aTaxonomy'] as $aTaxonomy) {
                if (is_tax($aTaxonomy['taxonomy'])) {
                    $template = plugin_dir_path(__DIR__) . 'templates/' . $aTaxonomy['taxonomy'] . '.php';
                    break 2;
                }
            }
        }
        return $template;
    }


}