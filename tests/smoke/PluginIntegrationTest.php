<?php

use AI_Assistant\Executor;
use AI_Assistant\File_Tool_Executor;
use AI_Assistant\Tools;
use PHPUnit\Framework\TestCase;

final class PluginIntegrationTest extends TestCase {

    public function test_core_executor_is_available_to_patch_assistant(): void {
        $this->assertTrue(class_exists(Executor::class));
        $this->assertTrue(class_exists(File_Tool_Executor::class));
        $this->assertInstanceOf(Executor::class, new Executor(new Tools()));
    }

    public function test_patch_tools_register_with_core_hooks(): void {
        $definitions = \AI_Assistant_Dev_Tools::register_tool_definitions([]);
        $names = array_column($definitions, 'name');

        $this->assertContains('write_file', $names);
        $this->assertContains('edit_file', $names);
        $this->assertContains('run_php', $names);
        $this->assertContains('install_plugin', $names);
    }
}
