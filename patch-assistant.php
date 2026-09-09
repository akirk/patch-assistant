<?php
/**
 * Plugin Name: Patch Assistant
 * Description: Development companion for AI Assistant with file editing, code execution, plugin installation, Git tracking, and WpApp tools.
 * Version: 1.0.0+f2dcdc4a8411
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Requires Plugins: ai-assistant
 * Author: Alex Kirk
 * License: GPL v2 or later
 * Text Domain: patch-assistant
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PATCH_ASSISTANT_VERSION', '1.0.0');
define('PATCH_ASSISTANT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PATCH_ASSISTANT_PLUGIN_URL', plugin_dir_url(__FILE__));

spl_autoload_register(function ($class) {
    $prefix = 'AI_Assistant\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = PATCH_ASSISTANT_PLUGIN_DIR . 'includes/class-' . strtolower(str_replace('_', '-', $relative)) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

function patch_assistant_init(): void {
    if (!function_exists('ai_assistant')) {
        return;
    }

    require_once PATCH_ASSISTANT_PLUGIN_DIR . 'dev-tools.php';
    add_filter('ai_assistant_file_tools_url', static function (): string {
        return PATCH_ASSISTANT_PLUGIN_URL . 'file-tools.php';
    });
    add_filter('ai_assistant_file_tools_token', static function ($token) {
        $settings = ai_assistant()->settings();
        return AI_Assistant\File_Tool_Auth::create_token(
            $settings->get_user_permission_level(),
            $settings->get_user_enabled_tools(),
            get_current_user_id()
        );
    });
    add_filter('ai_assistant_file_endpoint_tools', static function (): array {
        return ['read_file', 'find', 'list_directory', 'search_files', 'search_content', 'write_file', 'edit_file', 'delete_file'];
    });
    add_filter('ai_assistant_tool_meta', static function (array $tools): array {
        return array_merge([
            'read_file' => ['label' => 'Read File', 'group' => 'File Reading', 'dangerous' => false],
            'list_directory' => ['label' => 'List Directory', 'group' => 'File Reading', 'dangerous' => false],
            'search_files' => ['label' => 'Search Files', 'group' => 'File Reading', 'dangerous' => false],
            'search_content' => ['label' => 'Search Content', 'group' => 'File Reading', 'dangerous' => false],
        ], $tools);
    });
    add_filter('ai_assistant_tool_definitions', static function (array $tools): array {
        return array_merge([
            ['name' => 'read_file', 'description' => 'Read a file in wp-content.', 'parameters' => ['type' => 'object', 'properties' => ['path' => ['type' => 'string']], 'required' => ['path']]],
            ['name' => 'list_directory', 'description' => 'List files in wp-content.', 'parameters' => ['type' => 'object', 'properties' => ['path' => ['type' => 'string']], 'required' => ['path']]],
            ['name' => 'search_files', 'description' => 'Search for files in wp-content.', 'parameters' => ['type' => 'object', 'properties' => ['pattern' => ['type' => 'string'], 'directory' => ['type' => 'string']], 'required' => ['pattern']]],
            ['name' => 'search_content', 'description' => 'Search file contents in wp-content.', 'parameters' => ['type' => 'object', 'properties' => ['needle' => ['type' => 'string'], 'directory' => ['type' => 'string']], 'required' => ['needle']]],
        ], $tools);
    });
    add_filter('ai_assistant_default_enabled_tools', static function (array $tools): array {
        return array_merge(['read_file', 'list_directory', 'search_files', 'search_content'], $tools);
    });
    add_filter('ai_assistant_read_only_tool_names', static function (array $tools): array {
        return array_merge(['read_file', 'find', 'list_directory', 'search_files', 'search_content'], $tools);
    });

    $git_tracker_manager = new AI_Assistant\Git_Tracker_Manager();

    new AI_Assistant\Plugin_Downloads($git_tracker_manager);
    new AI_Assistant\Changes_Admin($git_tracker_manager);
    new AI_Assistant\Plugin_Recovery_Admin();
    $plugin_checkout_badge = new AI_Assistant\Plugin_Checkout_Badge($git_tracker_manager);
    new AI_Assistant\Chat_AI_Changes($plugin_checkout_badge);
    new AI_Assistant\Wp_App_Abilities($git_tracker_manager);
    new AI_Assistant\File_Abilities($git_tracker_manager);
    (new AI_Assistant\File_Access_Health())->register();
}

add_action('plugins_loaded', 'patch_assistant_init', 20);
