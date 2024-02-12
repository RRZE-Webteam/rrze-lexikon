<?php

/*
Plugin Name:     RRZE Lexikon
Plugin URI:      https://gitlab.rrze.fau.de/rrze-webteam/rrze-lexikon
Description:     Plugin, um FAQ, Synonyme und Glossare zu erstellen und mittels Shortcode oder als Block (Gutenberg Editor) einzubinden. FAQs können aus dem FAU-Netzwerk synchronisiert werden.
Version:         0.0.1
Requires at least: 6.1
Requires PHP:      8.0
Author:          RRZE Webteam
Author URI:      https://blogs.fau.de/webworking/
License:         GNU General Public License v2
License URI:     http://www.gnu.org/licenses/gpl-2.0.html
Domain Path:     /languages
Text Domain:     rrze-lexikon
 */

namespace RRZE\FAQ;

defined('ABSPATH') || exit;

require_once 'config/config.php';
use RRZE\FAQ\Main;

$s = array(
    '/^((http|https):\/\/)?(www.)+/i',
    '/\//',
    '/[^A-Za-z0-9\-]/',
);
$r = array(
    '',
    '-',
    '-',
);

define('CPT', [
    'faq' => [
        'singular' => __('FAQ', 'rrze-lexikon'),
        'plural' => __('FAQ', 'rrze-lexikon'), 
        'menu_icon' => 'dashicons-editor-help',
        'aTaxonomy' => [
            [
                'taxonomy' => 'faq_category',  
                'singular' => __('FAQ Category', 'rrze-lexikon'), 
                'plural' => __('FAQ Categories', 'rrze-lexikon'), 
                'hierarchical'=> TRUE, 
                'term' => ['source', 'lang']
            ], 
            [
                'taxonomy' => 'faq_tag',  
                'singular' => __('FAQ Tag', 'rrze-lexikon'), 
                'plural' => __('FAQ Tags', 'rrze-lexikon'), 
                'hierarchical'=> FALSE,
                'term' => ['source', 'lang']
            ]
        ],
    ],
    'glossary' => [
        'singular' => 'Glossary', 
        'plural' => 'Glossaries', 
        'menu_icon' => 'dashicons-book-alt',
        'aTaxonomy' => [
            [
                'taxonomy' => 'glossary_category',  
                'singular' => __('Glossary Category', 'rrze-lexikon'), 
                'plural' => __('Glossary Categories', 'rrze-lexikon'), 
                'hierarchical'=> TRUE, 
                'term' => ['source', 'lang']
            ], 
            [
                'taxonomy' => 'glossary_tag',  
                'singular' => __('Glossary Tag', 'rrze-lexikon'), 
                'plural' => __('Glossary Tags', 'rrze-lexikon'), 
                'hierarchical'=> FALSE,
                'term' => ['source', 'lang']
            ]
        ],
    ],
    'synonym' => [
        'singular' => 'Synonym', 
        'plural' => 'Synonyms', 
        'menu_icon' => 'dashicons-translation',
        'aTaxonomy' => [],
    ],
]);

define('FAQLOGFILE', plugin_dir_path(__FILE__) . 'rrze-lexikon-' . preg_replace($s, $r, get_bloginfo('url')) . '.log');

const RRZE_PHP_VERSION = '8.0';
const RRZE_WP_VERSION = '6.1';
const RRZE_PLUGIN_FILE = __FILE__;
const RRZE_SCHEMA_START = '<div style="display:none" itemscope itemtype="https://schema.org/FAQPage">';
const RRZE_SCHEMA_END = '</div>';
const RRZE_SCHEMA_QUESTION_START = '<div style="display:none" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><div style="display:none" itemprop="name">';
const RRZE_SCHEMA_QUESTION_END = '</div>';
const RRZE_SCHEMA_ANSWER_START = '<div style="display:none" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div style="display:none" itemprop="text">';
const RRZE_SCHEMA_ANSWER_END = '</div></div></div>';

// Automatische Laden von Klassen.
spl_autoload_register(function ($class) {
    $prefix = __NAMESPACE__;
    $base_dir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Registriert die Plugin-Funktion, die bei Aktivierung des Plugins ausgeführt werden soll.
register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
// Registriert die Plugin-Funktion, die ausgeführt werden soll, wenn das Plugin deaktiviert wird.
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');
// Wird aufgerufen, sobald alle aktivierten Plugins geladen wurden.
add_action('plugins_loaded', __NAMESPACE__ . '\loaded');

/**
 * Einbindung der Sprachdateien.
 */
function load_textdomain()
{
    load_plugin_textdomain('rrze-lexikon', false, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

/**
 * Überprüft die minimal erforderliche PHP- u. WP-Version.
 */
function system_requirements()
{
    $error = '';
    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
        /* Übersetzer: 1: aktuelle PHP-Version, 2: erforderliche PHP-Version */
        $error = sprintf(__('The server is running PHP version %1$s. The Plugin requires at least PHP version %2$s.', 'rrze-lexikon'), PHP_VERSION, RRZE_PHP_VERSION);
    } elseif (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        /* Übersetzer: 1: aktuelle WP-Version, 2: erforderliche WP-Version */
        $error = sprintf(__('The server is running WordPress version %1$s. The Plugin requires at least WordPress version %2$s.', 'rrze-lexikon'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }
    return $error;
}

/**
 * Wird durchgeführt, nachdem das Plugin aktiviert wurde.
 */
function activation()
{
    // Sprachdateien werden eingebunden.
    load_textdomain();

    // Überprüft die minimal erforderliche PHP- u. WP-Version.
    // Wenn die Überprüfung fehlschlägt, dann wird das Plugin automatisch deaktiviert.
    if ($error = system_requirements()) {
        deactivate_plugins(plugin_basename(__FILE__), false, true);
        wp_die($error);
    }

    // Ab hier können die Funktionen hinzugefügt werden,
    // die bei der Aktivierung des Plugins aufgerufen werden müssen.
    // Bspw. wp_schedule_event, flush_rewrite_rules, etc.
}

/**
 * Wird durchgeführt, nachdem das Plugin deaktiviert wurde.
 */
function deactivation()
{
    // Hier können die Funktionen hinzugefügt werden, die
    // bei der Deaktivierung des Plugins aufgerufen werden müssen.
    // Bspw. delete_option, wp_clear_scheduled_hook, flush_rewrite_rules, etc.

    // delete_option(Options::get_option_name());
    wp_clear_scheduled_hook('rrze_faq_auto_sync');
    flush_rewrite_rules();
}

function rrze_lexikon_init()
{
    register_block_type(__DIR__ . '/build');
}

/**
 * Wird durchgeführt, nachdem das WP-Grundsystem hochgefahren
 * und alle Plugins eingebunden wurden.
 */
function loaded()
{
    // Sprachdateien werden eingebunden.
    load_textdomain();

    // Überprüft die minimal erforderliche PHP- u. WP-Version.
    if ($error = system_requirements()) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugin_data = get_plugin_data(__FILE__);
        $plugin_name = $plugin_data['Name'];
        $tag = is_network_admin() ? 'network_admin_notices' : 'admin_notices';
        add_action($tag, function () use ($plugin_name, $error) {
            printf('<div class="notice notice-error"><p>%1$s: %2$s</p></div>', esc_html($plugin_name), esc_html($error));
        });
    } else {
        // Hauptklasse (Main) wird instanziiert.
        $main = new Main(__FILE__);
        $main->onLoaded();
    }

    add_action('init', __NAMESPACE__ . '\rrze_lexikon_init');

}
