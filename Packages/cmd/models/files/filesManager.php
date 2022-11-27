<?php

namespace ModelName;

/**
 * Class FilesManager
 * @package ModelName
 */
class FilesManager
{
    /**
     * @var array
     */
    private $undo;
    /**
     * @var array
     */
    private $queue;

    /**
     * @param $filePath
     * @return string
     */
    public static function file_type($filePath)
    {
        return substr($filePath, strrpos($filePath, '.') + 1);
    }

    /**
     * @param $seed
     * @return string
     */
    public static function generate_random_filename($seed)
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
     * @param $path
     * @return bool|null|resource
     */
    private function loadImg($path)
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
     * @param $actions
     */
    private function execute(&$actions)
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
    public function commit()
    {
        $this->execute($this->queue);
        $this->queue = [];
        $this->undo = [];
    }

    /**
     *
     */
    public function rollback()
    {
        $this->execute($this->undo);
        $this->queue = [];
        $this->undo = [];
    }

    /**
     * @param string $fileIndex
     * @param string $destinationFolder
     * @param string|null $fileName
     * @return bool|string
     */
    public function uploadFile($fileIndex, $destinationFolder, $fileName = null)
    {
        $fileName = ((!$fileName) ? $this->generate_random_filename($fileIndex) : $fileName) . '.' . file_type($_FILES[$fileIndex]['name']);

        $path = $destinationFolder . $fileName;
        if(! move_uploaded_file ( $_FILES[$fileIndex]['tmp_name'] , $path))
            return false;

        $this->undo[] = ['delete', $path];

        unset($path);

        return $fileName;
    }
}