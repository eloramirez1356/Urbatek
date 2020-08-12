<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    private $file;

    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->path;
    }

    public function setFile(File $file = null)
    {
        $this->file = $file;
    }

    public function upload($path)
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $file_name = $this->getFile() instanceof UploadedFile ? $this->getFile()->getClientOriginalName() : $this->getFile()->getFilename();
        $this->getFile()->move(
            $path,
            $file_name
        );


        // set the path property to the filename where you've saved the file
        //$this->path = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * Get file.
     *
     * @return File|UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getAbsolutePath().'/'.$this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return '/tmp';
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }
}