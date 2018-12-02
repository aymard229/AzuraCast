<?php
namespace App\Flysystem;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;

class StationFilesystem extends MountManager
{
    /**
     * Copy a file from the specified path to the temp directory
     *
     * @param string $from The permanent path to copy from
     * @param string|null $to The temporary path to copy to (temp://original if not specified)
     * @return string The temporary path
     */
    public function copyToTemp($from, $to = null): string
    {
        list($prefix_from, $path_from) = $this->getPrefixAndPath($from);

        if (null === $to) {
            $to = 'temp://'.$path_from;
        }

        if ($this->has($to)) {
            $this->delete($to);
        }

        $this->copy($from, $to);

        return $to;
    }

    /**
     * Update the value of a permanent file from a temporary directory.
     *
     * @param string $from The temporary path to update from
     * @param string $to The permanent path to update to
     * @param array $config
     * @return string
     */
    public function updateFromTemp($from, $to, array $config = []): string
    {
        $buffer = $this->readStream($from);
        if ($buffer === false) {
            return false;
        }

        $written = $this->forceWriteStream($to, $buffer, $config);

        if (is_resource($buffer)) {
            fclose($buffer);
        }

        if ($written) {
            $this->delete($from);
        }

        return $to;
    }

    /**
     * If the adapter associated with the specified URI is a local one, get the full filesystem path.
     *
     * NOTE: This can only be assured for the temp:// and config:// prefixes. Other prefixes can (and will)
     *       use non-local adapters that will trigger an exception here.
     *
     * @param string $uri
     * @return string
     */
    public function getFullPath($uri): string
    {
        list($prefix, $path) = $this->getPrefixAndPath($uri);

        $fs = $this->getFilesystem($prefix);

        if (!($fs instanceof Filesystem)) {
            throw new \InvalidArgumentException(sprintf('Filesystem for "%s" is not an instance of Filesystem.', $prefix));
        }

        $adapter = $fs->getAdapter();

        if ($adapter instanceof CachedAdapter) {
            $adapter = $adapter->getAdapter();
        }

        if (!($adapter instanceof Local)) {
            throw new \InvalidArgumentException(sprintf('Adapter for "%s" is not a Local or cached Local adapter.', $prefix));
        }

        $prefix = $adapter->getPathPrefix();
        return $prefix.DIRECTORY_SEPARATOR.$path;
    }

    /**
     * Write a new file, overwriting any file that already exists in the destination.
     *
     * @param string $path     The path of the new file.
     * @param string $contents The file contents.
     * @param array  $config   An optional configuration array.
     *
     * @return bool True on success, false on failure.
     */
    public function forceWrite($path, $contents, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        $fs = $this->getFilesystem($prefix);

        if ($fs->has($path)) {
            return $fs->update($path, $contents, $config);
        }

        return $fs->write($path, $contents, $config);
    }

    /**
     * Write a new file using a stream, overwriting any file that already exists in the destination.
     *
     * @param string   $path     The path of the new file.
     * @param resource $resource The file handle.
     * @param array    $config   An optional configuration array.
     *
     * @throws \InvalidArgumentException If $resource is not a file handle.
     *
     * @return bool True on success, false on failure.
     */
    public function forceWriteStream($path, $resource, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        $fs = $this->getFilesystem($prefix);
        if ($fs->has($path)) {
            return $fs->updateStream($path, $resource, $config);
        }

        return $fs->writeStream($path, $resource, $config);
    }
}