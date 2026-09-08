# Patch Assistant

Patch Assistant is the local development companion for [AI Assistant](../ai-assistant).

[Try Patch Assistant with AI Assistant in WordPress Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/akirk/patch-assistant/refs/heads/main/blueprint.json)

It adds the tools that should not ship in a WordPress.org directory plugin:

- **File tools**: Read, search, list, create, edit, and delete files inside `wp-content`, with path restrictions, protected-file checks, PHP linting, and change tracking.
- **PHP execution**: Run PHP in the WordPress environment with the same permission and confirmation flow as other dangerous tools.
- **Plugin tools**: Download, install, activate, recover, and inspect WordPress plugins.
- **AI Changes**: Review, diff, export, revert, reapply, combine, rename, and check out tracked file changes through the Tools > AI Changes screen.
- **File abilities**: Expose approved file operations as `ai/read-file`, `ai/write-file`, `ai/edit-file`, and `ai/delete-file` abilities for external agents.
- **WpApp abilities**: Create and convert WordPress apps, including the supporting files needed by a self-contained app.
- **Recovery and access checks**: Emergency plugin recovery and file-access health checks help recover from a broken plugin edit.

Install and activate `AI Assistant` first. This plugin is recommended for WordPress
Playground or trusted local development environments, not ordinary production
sites.

Pull requests automatically include a Playground link that uses the generated
`dist/<branch>` build for testing that change.
