<?php
namespace AI_Assistant;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds AI Changes context and settings to the standalone AI Assistant.
 */
class Chat_AI_Changes {

    private $plugin_checkout_badge;

    public function __construct(Plugin_Checkout_Badge $plugin_checkout_badge) {
        $this->plugin_checkout_badge = $plugin_checkout_badge;
        add_filter('ai_assistant_current_page_context', [$this, 'get_current_page_context']);
        add_filter('ai_assistant_chat_system_prompt', [$this, 'add_prompt_context'], 10, 2);
        add_filter('ai_assistant_ai_changes_url', [$this, 'get_ai_changes_url']);
        add_action('ai_assistant_settings_display_fields', [$this, 'render_settings_field']);
        add_action('admin_init', [$this, 'register_setting']);
    }

    public function get_current_page_context(): ?array {
        $metadata = $this->plugin_checkout_badge->get_current_ai_changes_metadata();
        return is_array($metadata) ? $metadata : null;
    }

    public function get_ai_changes_url(): string {
        return admin_url('tools.php?page=ai-changes');
    }

    public function add_prompt_context(string $system_prompt, ?array $metadata): string {
        if (empty($metadata['root']) || empty($metadata['url'])) {
            return $system_prompt;
        }

        return $system_prompt . "\n\nCURRENT PAGE FILE CHANGES:\n"
            . '- The plugin/theme rendering the current window has tracked AI file changes: ' . (string) $metadata['root'] . ".\n"
            . '- When useful, call navigate with url "' . (string) $metadata['url'] . '" and link_text "View changed files".\n';
    }

    public function register_setting(): void {
        register_setting('ai_assistant_settings', Plugin_Checkout_Badge::OPTION_SHOW_IN_PAGE_AI_CHANGES, [
            'type' => 'string',
            'sanitize_callback' => static function ($value): string { return $value ? '1' : ''; },
            'default' => '1',
        ]);
    }

    public function render_settings_field(): void {
        $option = Plugin_Checkout_Badge::OPTION_SHOW_IN_PAGE_AI_CHANGES;
        $enabled = get_option($option, '1');
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('In-page AI Changes', 'ai-assistant'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo esc_attr($option); ?>" value="1" <?php checked($enabled, '1'); ?>>
                        <?php esc_html_e('Always show in-page AI Changes', 'ai-assistant'); ?>
                    </label>
                    <p class="description"><?php esc_html_e('Show the compact AI Changes version log when a plugin or theme has tracked changes.', 'ai-assistant'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }
}
