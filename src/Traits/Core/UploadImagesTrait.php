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
trait UploadImagesTrait
{

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    public $image;

    /**
     * @var string $tstamp
     *
     * @ORM\Column(name="tstamp", type="string", length=50, nullable=true)
     */
    public $tstamp;

    /**
     * @Assert\File(
     *     maxSize = "9000k",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png","image/bmp","image/gif"},
     *     mimeTypesMessage = "Please upload a valid Image File"
     * )
     */
    protected $file;
    public $findex;

    /**
     * Set image
     *
     * @param integer $image
     * @return Pictures
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return integer 
     */
    public function getImage()
    {
        return $this->image;
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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {

        if ($file) {
            $this->file = $file;
        } else {
            $this->file = null;
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir() . '/' . $this->image;
    }

    public function getSymAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadSumRootDir() . '/' . $this->image;
    }

    public function getWebPath()
    {
        return null === $this->image ? null : $this->getUploadDir() . '/' . $this->image;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../public/' . $this->getUploadDir();
    }

    protected function getUploadSumRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../public/' . $this->getUploadSymDir();
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }

    public function getUploadSymDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }


    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function upload()
    {

        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {

            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $fname = md5($this->getFile()->getBasename() . $this->getStamp());
        $fname = $fname . '.' . $this->getFile()->guessExtension();


        $this->getFile()->move(
            $this->getUploadRootDir(),
            $fname
        );

        // clean up the file property as you won't need it anymore

        $this->file = null;


        // set the path property to the filename where you've saved the file
        $this->setImage($fname);
    }
}
