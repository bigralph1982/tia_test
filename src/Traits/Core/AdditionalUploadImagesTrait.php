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
trait AdditionalUploadImagesTrait {

    /**
     * @ORM\Column(name="imageAdditional", type="string", length=255, nullable=true)
     */
    public $imageAdditional;

    /**
     * @var string $tstamp
     *
     * @ORM\Column(name="tstampAdditional", type="string", length=50, nullable=true)
     */
    public $tstampAdditional;

    /**
     * @Assert\File(
     *     maxSize = "9000k",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png","image/bmp","image/gif"},
     *     mimeTypesMessage = "Please upload a valid Image File"
     * )
     */
    protected $fileAdditional;
    public $findexAdditional;

    /**
     * Set imageAdditional
     *
     * @param integer $image
     * @return Pictures
     */
    public function setImageAdditional($image) {
        $this->imageAdditional = $image;

        return $this;
    }

    /**
     * Get imageAdditional
     *
     * @return integer 
     */
    public function getImageAdditional() {
        return $this->imageAdditional;
    }

    /**
     * Set caption
     *
     * @param string $tstampAdditional
     * @return news
     */
    public function setTstampAdditional($tstamp) {
        $this->tstampAdditional = $tstamp;

        return $this;
    }

    /**
     * Get tstampAdditional
     *
     * @return string 
     */
    public function getTstampAdditional() {
        return $this->tstampAdditional;
    }

    public function getStampAdditional() {
        return uniqid() . strtotime(date("Y-m-d h:i:s"));
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFileAdditional(UploadedFile $file = null) {

        if ($file) {
            $this->fileAdditional = $file;
        } else {
            $this->fileAdditional = null;
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFileAdditional() {
        return $this->fileAdditional;
    }

    public function getAbsolutePathAdditional() {
        return null === $this->imageAdditional ? null : $this->getUploadRootDir() . '/' . $this->imageAdditional;
    }

    public function getWebPathAdditional() {
        return null === $this->imageAdditional ? null : $this->getUploadDir() . '/' . $this->imageAdditional;
    }



    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function uploadAdditional() {

        
        // the file property can be empty if the field is not required
        if (null === $this->getFileAdditional()) {

            return;
        }
        

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $fname = md5($this->getFileAdditional()->getBasename() . $this->getStampAdditional());
        $fname = $fname . '.' . $this->getFileAdditional()->guessExtension();


        $this->getFileAdditional()->move(
                $this->getUploadRootDir(), $fname
        );

        // clean up the file property as you won't need it anymore

        $this->fileAdditional = null;


        // set the path property to the filename where you've saved the file
        $this->setImageAdditional($fname);
    }

}
