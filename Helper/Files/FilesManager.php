<?php

/**
 * Class FilesManager
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
     * @param string $filePath
     * @return string
     */
    public static function fileType(string $filePath) : string
    {
        return substr($filePath, strrpos($filePath, '.') + 1);
    }

    /**
     * @param string $seed
     * @return string
     */
    public static function generateRandomFileName(string $seed) : string
    {
        return substr(md5( $seed . date("Y-m-d:H.i.s") . session_id() ), 0, 8) . date("YmdHis");
    }

    /**
     *
     */
    public function __construct()
    {
        $this->undo = [];
        $this->queue = [];
    }

    /**
     * @param string $path
     * @return bool|null|GdImage
     */
    private function loadImg(string $path) : bool|null|GdImage
    {
        $type = exif_imagetype($path);
        switch($type)
        {
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

        if(!$im)
            return null;
        return $im;
    }

    /**
     * @param array $actions
     * @return void
     */
    private function execute(array &$actions) : void
    {
        foreach($actions as $item)
        {
            switch($item[0])
            {
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
     *
     */
    public function commit() : void
    {
        $this->execute($this->queue);
        $this->queue = [];
        $this->undo = [];
    }

    /**
     *
     */
    public function rollback() : void
    {
        $this->execute($this->undo);
        $this->queue = [];
        $this->undo = [];
    }

    /**
     * @param string $fileIndex
     * @param string $destinationDirectory
     * @param string|null $fileName
     * @return bool|string
     */
    public function uploadFile(string $fileIndex, string $destinationDirectory, string $fileName = null) : bool|string
    {
        $fileName = ((!$fileName) ? self::generateRandomFileName($fileIndex) : $fileName) . '.' . self::fileType($_FILES[$fileIndex]['name']);

        $path = App::fullPath($destinationDirectory) . '/' . $fileName;
        if(! move_uploaded_file ( $_FILES[$fileIndex]['tmp_name'] , $path))
            return false;

        $this->undo[] = ['delete', $path];

        unset($path);

        return $fileName;
    }

    /**
     * @param string $directory
     * @param string $fileName
     * @param bool $delay
     * @return bool
     */
    public function delete(string $directory, string $fileName, bool $delay = true) : bool
    {
        $fullFileName = App::fullPath($directory) . '/' . $fileName;
        if($delay)
            // If delayed, set in queue
            $this->queue[] = ['delete', $fullFileName];
        else
        {
            if(is_file($fullFileName))
                return @unlink($fullFileName);

            return false;
        }

        return true;
    }

    /**
     * @param string $directory
     * @param string $fileName
     * @param string $newName
     * @param bool $delay
     * @return bool
     */
    public function rename(string $directory, string $fileName, string $newName, bool $delay = true) : bool
    {
        $sourceFileName = App::fullPath($directory) . '/' . $fileName;
        $destinationFileName = App::fullPath($directory) . '/' . $newName;

        if($delay)
            // If delayed, set in queue
            $this->queue[] = ['rename',[
                'from' => $sourceFileName,
                'to' => $destinationFileName
            ]];
        else
        {
            // Check the source file exists
            if(!is_file($sourceFileName))
                return false;
            // Check the new file do not exist
            if(is_file($destinationFileName))
                return false;

            return rename($sourceFileName, $destinationFileName);
        }

        return true;
    }
}