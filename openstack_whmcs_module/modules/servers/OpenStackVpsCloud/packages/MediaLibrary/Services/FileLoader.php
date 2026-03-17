<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Services;

use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\LibraryPathHelper;

class FileLoader
{
    protected string $name;

    protected string $content;

    protected int $size;

    protected string $file;

    public function __construct(string $filename)
    {
        $this->name = basename($filename);

        $this->file = LibraryPathHelper::getPath() . DIRECTORY_SEPARATOR . $this->name;
        if (!file_exists($this->file))
        {
            throw new \Exception('Image does not exist');
        }

        $this->size = filesize($this->file);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getType(): string
    {
        return \mime_content_type($this->file);
    }

    public function read(): string
    {
        return file_get_contents($this->file);
    }
}