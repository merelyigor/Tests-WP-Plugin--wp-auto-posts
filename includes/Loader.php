<?php

namespace WPAutoPosts;

class WPAutoPostsLoader
{
    public function __construct()
    {
        add_filter('cron_schedules', function ($schedules) {
            $schedules['every_3_minutes'] = [
                'interval' => 180, // 3 хвилини = 180 секунд
                'display' => __('Every 3 Minutes')
            ];
            return $schedules;
        });

        add_action('init', [$this, 'registerCron']);
        add_action('wp_auto_posts_daily', [$this, 'runImport']);
        add_action('wp_auto_posts_daily_2', [$this, 'runImport']);
    }

    public function registerCron()
    {
        if (!wp_next_scheduled('wp_auto_posts_daily')) {
            wp_schedule_event(time(), 'daily', 'wp_auto_posts_daily');
        }
        if (!wp_next_scheduled('wp_auto_posts_daily_2')) {
            wp_schedule_event(time(), 'every_3_minutes', 'wp_auto_posts_daily_2');
        }
    }

    public function runImport()
    {
        $importer = new WPAutoPostsImporter();
        $importer->import();
    }
}
