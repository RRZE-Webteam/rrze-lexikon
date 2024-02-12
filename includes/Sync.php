<?php

namespace RRZE\FAQ;

use function RRZE\FAQ\Config\logIt;
use RRZE\FAQ\API;

defined('ABSPATH') || exit;

class Sync
{

    public function doSync($mode)
    {
        $tStart = microtime(true);
        date_default_timezone_set('Europe/Berlin');
        $max_exec_time = ini_get('max_execution_time') - 40; // ini_get('max_execution_time') is not the correct value perhaps due to load-balancer or proxy or other fancy things I've no clue of. But this workaround works for now.
        $iCnt = 0;
        $api = new API();
        $domains = $api->getDomains();
        $options = get_option('rrze-lexikon');
        $allowSettingsError = ($mode == 'manual' ? true : false);
        $syncRan = false;
        foreach ($domains as $shortname => $url) {
            $tStartDetail = microtime(true);
            if (isset($options['faqsync_donotsync_' . $shortname]) && $options['faqsync_donotsync_' . $shortname] != 'on') {
                $categories = (isset($options['faqsync_categories_' . $shortname]) ? implode(',', $options['faqsync_categories_' . $shortname]) : false);
                if ($categories) {
                    $aCnt = $api->setFAQ($url, $categories, $shortname);
                    $syncRan = true;
                    foreach ($aCnt['URLhasSlider'] as $URLhasSlider) {
                        $error_msg = __('Domain', 'rrze-lexikon') . ' "' . $shortname . '": ' . __('Synchronization error. This FAQ contains sliders ([gallery]) and cannot be synchronized:', 'rrze-lexikon') . ' ' . $URLhasSlider;
                        logIt($error_msg . ' | ' . $mode);
                        if ($allowSettingsError) {
                            add_settings_error('Synchronization error', 'syncerror', $error_msg, 'error');
                        }
                    }
                    $sync_msg = __('Domain', 'rrze-lexikon') . ' "' . $shortname . '": ' . __('Synchronization completed.', 'rrze-lexikon') . ' ' . $aCnt['iNew'] . ' ' . __('new', 'rrze-lexikon') . ', ' . $aCnt['iUpdated'] . ' ' . __(' updated', 'rrze-lexikon') . ' ' . __('and', 'rrze-lexikon') . ' ' . $aCnt['iDeleted'] . ' ' . __('deleted', 'rrze-lexikon') . '. ' . __('Required time:', 'rrze-lexikon') . ' ' . sprintf('%.1f ', microtime(true) - $tStartDetail) . __('seconds', 'rrze-lexikon');
                    logIt($sync_msg . ' | ' . $mode);
                    if ($allowSettingsError) {
                        add_settings_error('Synchronization completed', 'synccompleted', $sync_msg, 'success');
                    }
                }
            }
        }

        if ($syncRan) {
            $sync_msg = __('All synchronizations completed', 'rrze-lexikon') . '. ' . __('Required time:', 'rrze-lexikon') . ' ' . sprintf('%.1f ', microtime(true) - $tStart) . __('seconds', 'rrze-lexikon');
        } else {
            $sync_msg = __('Settings updated', 'rrze-lexikon');
        }
        if ($allowSettingsError) {
            add_settings_error('Synchronization completed', 'synccompleted', $sync_msg, 'success');
            settings_errors();
        }
        logIt($sync_msg . ' | ' . $mode);
        return;
    }
}
