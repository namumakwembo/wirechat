<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Wirechat\Wirechat\Models\Action;
use Wirechat\Wirechat\Models\Attachment;
use Wirechat\Wirechat\Models\Conversation;
use Wirechat\Wirechat\Models\Group;
use Wirechat\Wirechat\Models\Message;
use Wirechat\Wirechat\Models\Participant;

beforeEach(function () {
    // Drop all tables to ensure clean state
    Schema::dropIfExists((new Action)->getTable());
    Schema::dropIfExists((new Attachment)->getTable());
    Schema::dropIfExists((new Group)->getTable());
    Schema::dropIfExists((new Participant)->getTable());
    Schema::dropIfExists((new Message)->getTable());
    Schema::dropIfExists((new Conversation)->getTable());
});

/**
 * Helper to check if a column type matches expected UUID type across different databases
 */
function isUuidColumnType(string $type): bool
{
    return match (strtolower($type)) {
        'varchar', 'char', 'uuid', 'character', 'nvarchar', 'uniqueidentifier', 'string' => true,
        default => false,
    };
}

/**
 * Helper to check if a column type matches expected integer type across different databases
 */
function isIntegerColumnType(string $type): bool
{
    return match (true) {
        str_contains(strtolower($type), 'int') => true, // matches int, bigint, integer, etc.
        default => false,
    };
}

describe('UUID configuration in migrations', function () {

    test('conversations table uses UUID when configured', function () {
        // Set UUID configuration
        Config::set('wirechat.uuids', true);

        // Run the migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000001_create_wirechat_conversations_table.php';
        $migration->up();

        // Check if id column is UUID type
        $columnType = Schema::getColumnType((new Conversation)->getTable(), 'id');
        expect(isUuidColumnType($columnType))->toBeTrue();
    });

    test('conversations table uses integer when UUID not configured', function () {
        // Set UUID configuration
        Config::set('wirechat.uuids', false);

        // Run the migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000001_create_wirechat_conversations_table.php';
        $migration->up();

        // Check if id column is integer type
        $columnType = Schema::getColumnType((new Conversation)->getTable(), 'id');
        expect(isIntegerColumnType($columnType))->toBeTrue();
    });

    test('messages table conversation_id uses UUID when configured', function () {
        // Set UUID configuration
        Config::set('wirechat.uuids', true);

        // Run conversations migration first
        $convMigration = include __DIR__.'/../../database/migrations/2024_11_01_000001_create_wirechat_conversations_table.php';
        $convMigration->up();

        // Run messages migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000003_create_wirechat_messages_table.php';
        $migration->up();

        // Check if conversation_id column is UUID type
        $columnType = Schema::getColumnType((new Message)->getTable(), 'conversation_id');
        expect(isUuidColumnType($columnType))->toBeTrue();
    });

    test('participants table conversation_id uses UUID when configured', function () {
        // Set UUID configuration
        Config::set('wirechat.uuids', true);

        // Run conversations migration first
        $convMigration = include __DIR__.'/../../database/migrations/2024_11_01_000001_create_wirechat_conversations_table.php';
        $convMigration->up();

        // Run participants migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000004_create_wirechat_participants_table.php';
        $migration->up();

        // Check if conversation_id column is UUID type
        $columnType = Schema::getColumnType((new Participant)->getTable(), 'conversation_id');
        expect(isUuidColumnType($columnType))->toBeTrue();
    });

    test('groups table conversation_id uses UUID when configured', function () {
        // Set UUID configuration
        Config::set('wirechat.uuids', true);

        // Run conversations migration first (not needed for schema check but good for foreign key)
        $convMigration = include __DIR__.'/../../database/migrations/2024_11_01_000001_create_wirechat_conversations_table.php';
        $convMigration->up();

        // Run groups migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000007_create_wirechat_groups_table.php';
        $migration->up();

        // Check if conversation_id column is UUID type
        $columnType = Schema::getColumnType((new Group)->getTable(), 'conversation_id');
        expect(isUuidColumnType($columnType))->toBeTrue();
    });

    test('attachments table should handle polymorphic UUID references when configured', function () {
        // Set UUID configuration
        Config::set('wirechat.uuids', true);

        // Run attachments migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000002_create_wirechat_attachments_table.php';
        $migration->up();

        // For polymorphic relationships that could reference UUID models,
        // the attachable_id should be UUID type
        $columnType = Schema::getColumnType((new Attachment)->getTable(), 'attachable_id');

        expect(isUuidColumnType($columnType))->toBeTrue();
    });

    test('actions table should handle polymorphic UUID references when configured', function () {
        // Set UUID configuration
        Config::set('wirechat.uuids', true);

        // Run actions migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000006_create_wirechat_actions_table.php';
        $migration->up();

        // For polymorphic relationships that could reference UUID models (like conversations),
        // the actionable_id and actor_id should be UUID type
        $actionableType = Schema::getColumnType((new Action)->getTable(), 'actionable_id');
        $actorType = Schema::getColumnType((new Action)->getTable(), 'actor_id');

        expect(isUuidColumnType($actionableType))->toBeTrue();
        expect(isUuidColumnType($actorType))->toBeTrue();
    });
});

describe('UUID configuration in migrations with integer IDs', function () {

    test('attachments table uses integer for polymorphic ids when UUID not configured', function () {
        // Set UUID configuration to false
        Config::set('wirechat.uuids', false);

        // Run attachments migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000002_create_wirechat_attachments_table.php';
        $migration->up();

        // Check if attachable_id column is integer type
        $columnType = Schema::getColumnType((new Attachment)->getTable(), 'attachable_id');
        expect(isIntegerColumnType($columnType))->toBeTrue();
    });

    test('actions table uses integer for polymorphic ids when UUID not configured', function () {
        // Set UUID configuration to false
        Config::set('wirechat.uuids', false);

        // Run actions migration
        $migration = include __DIR__.'/../../database/migrations/2024_11_01_000006_create_wirechat_actions_table.php';
        $migration->up();

        // Check if actionable_id and actor_id columns are integer type
        $actionableType = Schema::getColumnType((new Action)->getTable(), 'actionable_id');
        $actorType = Schema::getColumnType((new Action)->getTable(), 'actor_id');

        expect(isIntegerColumnType($actionableType))->toBeTrue();
        expect(isIntegerColumnType($actorType))->toBeTrue();
    });
});
