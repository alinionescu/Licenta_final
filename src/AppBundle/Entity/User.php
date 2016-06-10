<?php
/**
 * Created by PhpStorm.
 * User: alin
 * Date: 13.03.2016
 * Time: 13:35
 */

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Avanzu\AdminThemeBundle\Model\UserInterface as ThemeUser;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser implements ThemeUser
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Person
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Person", cascade={"persist"}, mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $person;

    /**
     * @Assert\File(maxSize="4096k")
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $profilePictureFile;

    private $tempProfilePicturePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $profilePicturePath;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     * @return User
     */
    public function setPerson($person)
    {
        $person->setUser($this);
        $this->person = $person;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTempProfilePicturePath()
    {
        return $this->tempProfilePicturePath;
    }

    /**
     * @param mixed $tempProfilePicturePath
     * @return User
     */
    public function setTempProfilePicturePath($tempProfilePicturePath)
    {
        $this->tempProfilePicturePath = $tempProfilePicturePath;
        return $this;
    }

    /**
     * Sets the file used for profile picture uploads
     *
     * @param UploadedFile $file
     * @return object
     */
    public function setProfilePictureFile(UploadedFile $file = null)
    {
        // set the value of the holder
        $this->profilePictureFile       =   $file;
        // check if we have an old image path
        if (isset($this->profilePicturePath)) {
            // store the old name to delete after the update
            $this->tempProfilePicturePath = $this->profilePicturePath;
            $this->profilePicturePath = null;
        } else {
            $this->profilePicturePath = 'initial';
        }

        return $this;
    }

    /**
     * Set profilePicturePath
     *
     * @param string $profilePicturePath
     * @return User
     */
    public function setProfilePicturePath($profilePicturePath)
    {
        $this->profilePicturePath = $profilePicturePath;

        return $this;
    }

    /**
     * Get profilePicturePath
     *
     * @return string
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * Get the absolute path of the profilePicturePath
     */
    public function getProfilePictureAbsolutePath()
    {
        return null === $this->profilePicturePath
            ? null
            : $this->getUploadRootDir().'/'.$this->profilePicturePath;
    }

    /**
     * Get root directory for file uploads
     *
     * @return string
     */
    protected function getUploadRootDir($type='profilePicture')
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir($type);
    }

    /**
     * Specifies where in the /web directory profile pic uploads are stored
     *
     * @return string
     */
    protected function getUploadDir($type='profilePicture')
    {
        // the type param is to change these methods at a later date for more file uploads
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/user/profilepics';
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebProfilePicturePath()
    {

        return '/'.$this->getUploadDir().'/'.$this->getProfilePicturePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeProfilePictureFile()
    {
        if ($file = $this->getProfilePictureAbsolutePath() && file_exists($this->getProfilePictureAbsolutePath())) {
            unlink($file);
        }
    }

    /**
     * Get the file used for profile picture uploads
     *
     * @return UploadedFile
     */
    public function getProfilePictureFile()
    {
        return $this->profilePictureFile;
    }

    public function getAvatar()
    {
        return $this->profilePicturePath;
    }

    public function getName()
    {
        return $this->username;
    }

    public function getMemberSince()
    {
        // TODO: Implement getMemberSince() method.
    }

    public function isOnline()
    {
        return true;
    }

    public function getIdentifier()
    {
        // TODO: Implement getIdentifier() method.
    }

    public function getTitle()
    {
        return "MR.";
    }
}