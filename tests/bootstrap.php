<?php

$ai_assistant_dir = dirname(__DIR__) . '/ai-assistant';
if (!is_dir($ai_assistant_dir)) {
    $ai_assistant_dir = dirname(__DIR__) . '/../ai-assistant';
}
require_once $ai_assistant_dir . '/tests/bootstrap.php';

foreach ([
    '/includes/class-file-tool-auth.php',
    '/includes/class-emergency-plugin-guard.php',
    '/includes/class-file-tool-executor.php',
    '/includes/class-plugin-recovery-admin.php',
    '/includes/class-git-tracker.php',
    '/includes/class-git-tracker-manager.php',
    '/includes/class-plugin-checkout-badge.php',
    '/includes/class-wp-app-abilities.php',
    '/includes/class-file-abilities.php',
    '/includes/class-file-access-health.php',
    '/includes/class-changes-admin.php',
] as $patch_file) {
    require_once dirname(__DIR__) . $patch_file;
}

require_once dirname(__DIR__) . '/dev-tools.php';

AI_Assistant_Dev_Tools::init();

add_filter('ai_assistant_tool_meta', static function (array $tools): array {
    return array_merge([
        'read_file' => ['label' => 'Read File', 'group' => 'File Reading', 'dangerous' => false],
        'list_directory' => ['label' => 'List Directory', 'group' => 'File Reading', 'dangerous' => false],
        'search_files' => ['label' => 'Search Files', 'group' => 'File Reading', 'dangerous' => false],
        'search_content' => ['label' => 'Search Content', 'group' => 'File Reading', 'dangerous' => false],
    ], $tools);
});
add_filter('ai_assistant_default_enabled_tools', static function (array $tools): array {
    return array_merge(['read_file', 'list_directory', 'search_files', 'search_content'], $tools);
});
add_filter('ai_assistant_read_only_tool_names', static function (array $tools): array {
    return array_merge(['read_file', 'find', 'list_directory', 'search_files', 'search_content'], $tools);
});
add_filter('ai_assistant_execute_file_tool', static function ($result, string $tool_name, array $arguments, ?int $conversation_id = null): array {
    return (new \AI_Assistant\File_Tool_Executor(
        WP_CONTENT_DIR
    ))->execute($tool_name, $arguments, $conversation_id);
}, 10, 4);
