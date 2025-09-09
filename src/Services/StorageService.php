<?php

namespace Wirechat\Wirechat\Services;

class StorageService
{
    /**
     * Get the wirechat storage disk from the configuration.
     *
     * @return string The storage disk.
     */
    public static function disk(): string
    {
        return (string) config('wirechat.storage.disk')
            ?: config('wirechat.attachments.storage_disk', 'public');
    }

    /**
     * Get the configured storage visibility for Wirechat.
     *
     * Reads from `wirechat.storage.visibility`.
     * Falls back to `wirechat.attachments.disk_visibility`.
     *
     * @return string Either 'public' or 'private'.
     */
    public static function visibility(): string
    {
        return (string) config('wirechat.storage.visibility')
            ?: config('wirechat.attachments.disk_visibility', 'public');
    }

    /**
     * --------------------
     * Directories
     * i.e attachments , reports etc
     * -----------------
     */

    /**
     * Attachments directory
     *
     * @return string The storage directory path.
     */
    public static function attachmentsDirectory(): string
    {
        return (string) config('wirechat.storage.directories.attachments')
            ?: config('wirechat.attachments.storage_folder', 'attachments');
    }
}
