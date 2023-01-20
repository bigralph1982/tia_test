<?php

namespace App\Traits\Core;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users  
 * @MappedSuperClass 
 * @ORM\HasLifecycleCallbacks() 
 */
trait UploadFileTrait
{

    /**
     * @ORM\Column(name="filepath", type="string", length=255, nullable=true)
     */
    public $filepath;

    /**
     * @var string $tstamp
     *
     * @ORM\Column(name="tstamp", type="string", length=50, nullable=true)
     */
    public $tstamp;

    /**
     * @Assert\File(
     *     maxSize = "65000k",
     *     maxSizeMessage = "The file size must be under 10MB ",
     *     mimeTypes = {"application/pdf"},
     *     mimeTypesMessage = "The file must be PDF "
     * )
     */
    protected $uploadfile;
    public $findex;

    /**
     * Set filepath
     *
     * @param integer $filepath
     * @return Pictures
     */
    public function setFilePath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get filepath
     *
     * @return integer 
     */
    public function getFilePath()
    {
        return $this->filepath;
    }

    /**
     * Set caption
     *
     * @param string $tstamp
     * @return news
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    /**
     * Get tstamp
     *
     * @return string 
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    public function getStamp()
    {
        return uniqid() . strtotime(date("Y-m-d h:i:s"));
    }

    /**
     * Sets uploadfile.
     *
     * @param UploadedUploadFile $uploadfile
     */
    public function setUploadFile(UploadedFile $uploadfile = null)
    {

        if ($uploadfile) {
            $this->uploadfile = $uploadfile;
        } else {
            return;
        }
    }

    /**
     * Get uploadfile.
     *
     * @return UploadedUploadFile
     */
    public function getUploadFile()
    {
        return $this->uploadfile;
    }

    public function getFileAbsolutePath()
    {
        return null === $this->filepath ? null : $this->getFileUploadRootDir() . '/' . $this->filepath;
    }

    public function getFileWebPath()
    {
        return null === $this->filepath ? null : $this->getFileUploadDir() . '/' . $this->filepath;
    }

    protected function getFileUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getFileUploadDir();
    }

    public function getFileUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/files';
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     * 
     */
    public function uploadFile()
    {

        // the file property can be empty if the field is not required
        if (null === $this->getUploadFile()) {
            return;
        }


        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $fname = md5($this->getUploadFile()->getBasename() . $this->getStamp());
        $fname = $fname . '.' . $this->getUploadFile()->guessExtension();


        $this->getUploadFile()->move(
            $this->getFileUploadRootDir(),
            $fname
        );

        // clean up the file property as you won't need it anymore

        $this->uploadfile = null;


        // set the path property to the filename where you've saved the file
        $this->setFilePath($fname);
    }

    public function unlinkFile($file)
    {
        unlink($this->getFileUploadRootDir() . "/" . $file);
    }
}
