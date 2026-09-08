# Patch Assistant

Patch Assistant is the local development companion for [AI Assistant](../ai-assistant).

It adds the tools that should not ship in a WordPress.org directory plugin:

- reading, writing, editing, searching, and deleting files in `wp-content`
- raw PHP execution
- installing and activating plugins
- Git-backed AI Changes, recovery, and plugin ZIP downloads
- file abilities for external agents
- WpApp/plugin scaffolding abilities

Install and activate `AI Assistant` first. This plugin is recommended for WordPress
Playground or trusted local development environments, not ordinary production
sites.

## Try it in WordPress Playground

[Try Patch Assistant with AI Assistant in WordPress Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/akirk/patch-assistant/refs/heads/main/blueprint.json)

Pull requests automatically include a Playground link that uses the generated
`dist/<branch>` build for testing that change.
