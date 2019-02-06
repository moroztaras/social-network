<?php

namespace App\Components\File;

use App\Components\Utils\SchemaReader;
use App\Entity\File;
use Gumlet\ImageResize;
use Symfony\Component\Filesystem\Filesystem;

class ImageStyle
{
    private $fileSystem;

    private $fileManager;

    public $imageExtension = ['jpg', 'png', 'jpeg'];

    private $imageStyleList = [];

    public function __construct(SchemaReader $schema_reader, FileAssistant $file_assistant)
    {
        $this->fileSystem = new Filesystem();
        $this->fileManager = $file_assistant;
        $this->schemaReader = $schema_reader;
        $this->imageStyleList = $this->schemaReader->getSchema('image_style');
    }

    public function existStyle($style_name)
    {
        return isset($this->imageStyleList[$style_name]);
    }

    public function getStyle($style_name)
    {
        return $this->imageStyleList[$style_name];
    }

    public function privateTrash(File $file, $image_style)
    {
        $fileTrash = 'private://image_style/'.$image_style.'/'.$file->getFilename();
        $dirRoot = $this->fileManager->uploadFolderDir('image_style/'.$image_style, true);
        $fileRootDir = $dirRoot.'/'.$file->getFilename();
        if (is_file($fileRootDir)) {
            return $fileTrash;
        }

        $this->resizeImage($file, $image_style, $fileRootDir);

        return $fileTrash;
    }

    /**
     * @param File $file
     * @param $style_name
     *
     * @return null|string
     */
    public function styleImage(File $file = null, $style_name)
    {
        if (!$file) {
            return null;
        }
        if ($this->existStyle($style_name)) {
            $dirRoot = $this->fileManager->uploadFolderDir('image_style/'.$style_name);
            $fileRootDir = $dirRoot.'/'.$file->getFilename();
            $fileWebDir = 'public://image_style/'.$style_name.'/'.$file->getFilename();

            if (is_file($fileRootDir)) {
                return $fileWebDir;
            }

            $this->resizeImage($file, $style_name, $fileRootDir);

            return $fileWebDir;
        }

        return null;
    }

    /**
     * @param File $file
     * @param $style_name
     * @param $newPath
     */
    public function resizeImage(File $file, $style_name, $newPath = null)
    {
        $url = $file->getUrl();
        if (!$newPath) {
            $newPath = $this->fileManager->rootUrl($url);
        }
        $imageResize = new ImageResize($this->fileManager->rootUrl($url));
        $this->actionResizeImage($imageResize, $style_name);
        $imageResize->save($newPath);
    }

    public function actionResizeImage(ImageResize $image_resize, $style_name)
    {
        $styleImage = $this->getStyle($style_name);

        foreach ($styleImage['style'] as $name => $style) {
            $action = $style['action'];
            $width = $style['width'];
            $height = isset($style['height']) ? $style['height'] : 0;

            switch ($action) {
        case 'to_width':
          $image_resize->resizeToWidth($width);
          break;
        case 'scale_crop':
          $image_resize->crop($width, $height, true);
          break;
        case 'crop':
          $image_resize->crop($width, $height);
          break;
        case 'resize':
          $image_resize->resize($width, $height);
          break;
        default:
          $image_resize->resizeToBestFit($width, $height);
          break;
      }
        }
    }

    /**
     * @return FileAssistant
     *
     * @deprecated
     */
    public function getFileManager()
    {
        return $this->fileManager;
    }

    public function getFileAssistant()
    {
        return $this->fileManager;
    }
}
