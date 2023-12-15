<?php

namespace Arkit\Helper\Files;

/**
 * Manage files as atomic operations. If something is wrong, just rollback.
 */
class FilesManager
{
    /**
     * @var array
     */
    private array $undo;
    /**
     * @var array
     */
    private array $queue;

    /**
     * Get the file type
     * 
     * @param string $filePath Full file path
     * @return string
     */
    public static function fileType(string $filePath): string
    {
        return substr($filePath, strrpos($filePath, '.') + 1);
    }

    /**
     * Generate a random file name given an seed
     * 
     * @param string $seed Seed
     * @return string
     */
    public static function generateRandomFileName(string $seed): string
    {
        return substr(md5($seed . date("Y-m-d:H.i.s") . session_id()), 0, 8) . date("YmdHis");
    }

    /**
     * Constructor of the class. Initialize all internal variables.
     */
    public function __construct()
    {
        $this->undo = [];
        $this->queue = [];
    }

    /**
     * @param string $path
     * @return bool|null|\GdImage
     */
    private function loadImg(string $path): bool|null|\GdImage
    {
        $type = exif_imagetype($path);
        switch ($type) {
            case 1:
                $im = imagecreatefromgif($path);
                break;
            case 2:
                echo 'jpg';
                $im = imagecreatefromjpeg($path);
                break;
            case 3:
                $im = imagecreatefrompng($path);
                break;
            case 6:
                $im = imagecreatefrombmp($path);
                break;
            default:
                $im = false;
                break;
        }

        if (!$im)
            return null;
        return $im;
    }

    /**
     * Execute all pending actions
     * 
     * @param array $actions Array of actions to execute
     * @return void
     */
    private function execute(array &$actions): void
    {
        foreach ($actions as $item) {
            switch ($item[0]) {
                case 'delete':
                    @unlink($item[1]);
                    break;

                case 'rename':
                    $info = $item[1];
                    @rename($info['from'], $info['to']);
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Commit all pending changes
     *
     * @return void
     */
    public function commit(): void
    {
        $this->execute($this->queue);
        $this->queue = [];
        $this->undo = [];
    }

    /**
     * Rollback all pending changes
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->execute($this->undo);
        $this->queue = [];
        $this->undo = [];
    }

    /**
     * Upload a file
     * 
     * @param string $fileIndex File index into $_FILE array
     * @param string $destinationDirectory Destination directory
     * @param string|null $fileName New file name. If not set, conserve the original
     * @return bool|string Return the name of the file or false if any error.
     */
    public function uploadFile(string $fileIndex, string $destinationDirectory, string $fileName = null): bool|string
    {
        $fileName = ((!$fileName) ? self::generateRandomFileName($fileIndex) : $fileName) . '.' . self::fileType($_FILES[$fileIndex]['name']);

        $path = \Arkit\App::fullPath($destinationDirectory) . '/' . $fileName;
        if (!move_uploaded_file($_FILES[$fileIndex]['tmp_name'], $path))
            return false;

        $this->undo[] = ['delete', $path];

        unset($path);

        return $fileName;
    }

    /**
     * Delete a file
     * 
     * @param string $directory Directory path where the file is located
     * @param string $fileName File name
     * @param bool $delay Set false for delete at the moment, leave true for delete when commit.
     * @return bool
     */
    public function delete(string $directory, string $fileName, bool $delay = true): bool
    {
        $fullFileName = \Arkit\App::fullPath($directory) . '/' . $fileName;
        if ($delay)
            // If delayed, set in queue
            $this->queue[] = ['delete', $fullFileName];
        else {
            if (is_file($fullFileName))
                return @unlink($fullFileName);

            return false;
        }

        return true;
    }

    /**
     * Rename a file
     * 
     * @param string $directory Directory path where the file is located
     * @param string $fileName File name
     * @param string $newName New file name
     * @param bool $delay Set false for rename at the moment, leave true for rename when commit.
     * @return bool
     */
    public function rename(string $directory, string $fileName, string $newName, bool $delay = true): bool
    {
        $sourceFileName = \Arkit\App::fullPath($directory) . '/' . $fileName;
        $destinationFileName = \Arkit\App::fullPath($directory) . '/' . $newName;

        if ($delay)
            // If delayed, set in queue
            $this->queue[] = ['rename', [
                'from' => $sourceFileName,
                'to' => $destinationFileName
            ]];
        else {
            // Check the source file exists
            if (!is_file($sourceFileName))
                return false;
            // Check the new file do not exist
            if (is_file($destinationFileName))
                return false;

            return rename($sourceFileName, $destinationFileName);
        }

        return true;
    }
}