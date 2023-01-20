<?php
namespace App\Entity\Core\Users;

use App\Entity\Production\Roles\Roles;
use App\Repository\Core\Users\UsersRepository;
use App\Traits\Core\DatesTrait;
use App\Traits\Core\KeywordTrait;
use App\Traits\Core\StatusTrait;
use App\Traits\Core\UploadImagesTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface; 

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Doctrine\ORM\Mapping\MappedSuperclass;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * 
 * 
 * @UniqueEntity({"username"}, groups={"createAccount"}, repositoryMethod="isUsernameAllowed" , message="This Username is already in use.")
 * 
 * @UniqueEntity({"username","id"}, groups={ "editAccount"}, repositoryMethod="isUsernameAllowed" , message="This Username is already in use.")
 * 
 * @UniqueEntity({"email"}, groups={"createAccount"}, repositoryMethod="isEmailAllowed" , message="This Email is already in use.")
 * @UniqueEntity({"email","id"}, groups={ "editAccount"}, repositoryMethod="isEmailAllowed" , message="This Email is already in use.")
 * 
 * 
 * 
 * 
 * @ORM\HasLifecycleCallbacks() 
 * 
 * @MappedSuperClass
 * 
 */
class Users implements UserInterface, EquatableInterface
{

    use StatusTrait;
    use DatesTrait;
    use UploadImagesTrait;
    use KeywordTrait;

    protected $passMinLength = 6;
    protected $passMinDigits = 6;
    protected $passMaxLength = 25;
    protected $complexity = true;

    public $roles_array = [
        999 => 'ROLE_DEVELOPER',
        1 => 'ROLE_SUPER_ADMIN',
        2 => 'ROLE_ADMIN',
        3 => 'ROLE_USER',
    ];



    /*
     * @Recaptcha\IsTrue()
     */
    public $recaptcha;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=128)
     * @Assert\Length(
     *      min = 6,
     *      max = 128, 
     *      minMessage="The username must be more than {{limit}} characters",
     *      maxMessage="The username must be less than {{limit}} characters",
     *      groups={"editAccount","createAccount"}
     * )
     * 
     *
     */
    public $username;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=128)
     * @Assert\Length(
     *      min = 6,
     *      max = 128, 
     *      groups={"editAccount","createAccount"}
     * )
     * @Assert\Email(
     *  message = "The email '{{ value }}' is not a valid email.",
     *  groups={"editAccount","createAccount"}
     * )
     *
     */
    private $email;

    /**
     * @var string $username
     *
     *  
     * @Assert\Length(
     *      min = 6,
     *      max = 70, 
     * )
     * @Assert\Email(
     *  message = "The email '{{ value }}' is not a valid email."
     * )
     *
     */
    public $oldUsername;

    /**
     * @var string $username
     *  
     * @Assert\Length(
     *      min = 6,
     *      max = 70, 
     * )
     * @Assert\Email(
     *  message = "The email '{{ value }}' is not a valid email."
     * )
     *
     */
    public $repeatUsername;

    /**
     * @var string $username
     *  
     * @Assert\Length(
     *      min = 6,
     *      max = 70, 
     * )
     * @Assert\Email(
     *  message = "The email '{{ value }}' is not a valid email."
     * )
     *
     */
    public $newUsername;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=true)
     */
    protected $password;

    /**
     * @SecurityAssert\UserPassword(
     *      groups={"edit-password", "edit-username"},
     *     message = "Wrong value for your current password"
     *  )
     */
    protected $oldPassword;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255,nullable=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 128, 
     *      groups={"editAccount","createAccount"}
     * )
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(name="fname", type="string", length=255,nullable=true)
     *  @Assert\Length(
     *      min = 2,
     *      max = 70, 
     *      groups={"editAccount","createAccount"}
     * )
     */
    public $fname;

    /**
     * @var string
     *
     * @ORM\Column(name="lname", type="string", length=255,nullable=true)
     *  @Assert\Length(
     *      min = 2,
     *      max = 70, 
     *      groups={"editAccount","createAccount"}
     * )
     */
    public $lname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255,nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 20, 
     *      groups={"editAccount","createAccount"}
     * )
     */
    public $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255,nullable=true)
     *  @Assert\Length(
     *      min = 3,
     *      max = 70, 
     *      groups={"editAccount","createAccount"}
     * )
     */
    public $address;

    /**
     * @var string
     *
     * @ORM\Column(name="addedByAdmin", type="boolean",   nullable=true)
     */
    private $addedByAdmin;

    /**
     * @var string
     *
     * @ORM\Column(name="registeredOnMobile", type="boolean",   nullable=true)
     */
    private $registeredOnMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="isGuest", type="boolean",   nullable=true)
     */
    private $isGuest;

    /**
     * @var \DateTimeInterface 
     *
     * @ORM\Column(name="dateOfBirth", type="date", nullable=true)
     */
    protected $dob;

    /**
     * @var bool $isActive
     *
     * @ORM\Column(name="is_locked", type="boolean", nullable=true)
     */
    protected $isLocked;

    /**
     * @var Integer $role_id;
     * 
     * @ORM\Column(name="gender", type="smallint", nullable=true) 
     */
    protected $gender;
    public $gender_array = array('form.female' => 1, 'form.male' => 2);

    /*
     */
    public $api_image;

    /**
     * @var bool $isActive
     *
     * @ORM\Column(name="is_active", type="smallint")
     */
    protected $isActive;

    /**
     * @var \DateTimeInterface $lastLogin
     *
     * @ORM\Column(name="lastLogin", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var string $creator_ip
     *
     * @ORM\Column(name="creator_ip", type="string", length=255, nullable=true )
     */
    protected $creator_ip;

    /**
     * @var string $updator_ip
     *
     * @ORM\Column(name="updator_ip", type="string", length=255, nullable=true)
     */
    protected $updator_ip;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    protected $slug;


    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string", length=255, nullable=true)
     */
    protected $timezone;

    /**
     * @var \DateTimeInterface $createDate
     *
     * @ORM\Column(name="registrationConfirmationDate", type="datetime", nullable=true)
     */
    protected $registrationConfirmationDate;

    /**
     * @var int
     * #1 on-site reg, 2-fb reg, 3-twitter reg, 4=>google reg, 5=>linkedin reg
     * @ORM\Column(name="registrationType", type="smallint", nullable=true)
     */
    protected $registrationType;

    /**
     * @var \DateTimeInterface $createDate
     *
     * @ORM\Column(name="passwordConfirmationDate", type="datetime", nullable=true)
     */
    protected $passwordConfirmationDate;

    /**
     * @ORM\Column(name="salt", type="string", length=50, nullable=true)
     */
    public $salt;

    /**
     * @var int
     *
     * @ORM\Column(name="loginCount", type="integer",nullable=true, options={"default" : 0})
     */
    public $loginCount;

    /**
     * @var Integer $role_id;
     * 
     * @ORM\Column(name="role_id", type="smallint", nullable=true) 
     */
    protected $role_id;


    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];


    /*
     * 
     */
    public $text_password;

    /**
     * @var int $indexrandomnumber
     *
     * @ORM\Column(name="indexrandomnumber", type="integer", nullable=true)
     */
    protected $indexrandomnumber;

    public function __construct()
    {

        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier()
    {
        return $this->username;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        if(in_array("ROLE_DEVELOPER", $roles) || in_array("ROLE_SUPER_ADMIN", $roles) || in_array("ROLE_ADMIN", $roles)){
            $roles = array_merge($roles, Roles::getAllPermissions());
        }
        return array_unique($roles);
    }

    public function getRolesArray()
    {
        return $this->roles_array;
    }

    public function getShowRolesArray($dev = null)
    {
        $roles = [];

        $array = array_flip($this->roles_array);

        if (!$dev) {
            unset($array["ROLE_DEVELOPER"]);
        }
        unset($array["ROLE_USER"]);

        foreach ($array as $key => $val) {
            $roles["roles." . $key] = $val;
        }


        return $roles;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): ?string
    {
        return (string) $this->username;
    }


    public function eraseCredentials()
    {
    }

    public function setRandomSalt()
    {
    }

    /**
     * @Assert\IsTrue(groups={"edit-username"}, message = "validation.errors.usernamesmustmatch")
     */
    public function isOldUsernameMatchingNewUsername()
    {

        return $this->username === $this->oldUsername;
    }

    /**
     * @Assert\IsTrue(groups={"edit-username"}, message = "validation.errors.newusernamesmustmatch")
     */
    public function isRepeatUsernameMatchingNewUsername()
    {

        return $this->newUsername === $this->repeatUsername;
    }

    /**
     * @Assert\IsTrue(groups={"Signup"}, message = "The password cannot match your username")
     */
    public function isPasswordMatchingUsername()
    {
        if ($this->text_password)
            return $this->username !== $this->text_password;
    }

    /**
     * @Assert\IsTrue(groups={"createAccount", "editAccount"}, message = "The password must include at least 6 characters, 1 uppercase, 1 special character (@, !, ?, -, etc.) and 1 number")
     */
    public function isPasswordLegal()
    {

        $errors = [];

        if ($this->username or $this->id or $this->text_password) {
            if (!$this->id) {
                if (!$this->passwordLengthValidation()) {
                    $errors[] = true;
                }

                if ($this->complexity) {
                    if (!$this->passwordCharactersValidation()) {
                        $errors[] = true;
                    }
                }
            } else {

                if ($this->text_password) {

                    if (!$this->passwordLengthValidation()) {
                        $errors[] = true;
                    }

                    if ($this->complexity) {
                        if (!$this->passwordCharactersValidation()) {
                            $errors[] = true;
                        }
                    }
                }
            }
        } else {
            $errors[] = true;
        }


        if (count($errors) > 0) {
            return false;
        } else {
            return true;
        }
    }
    
    protected function passwordLengthValidation()
    {
        if (strlen($this->text_password) >= $this->passMinLength and strlen($this->text_password) <= $this->passMaxLength) {

            return true;
        } else {
            return false;
        }
    }

    protected function passwordCharactersValidation()
    {
        $containsLc = preg_match('/[a-z]/', $this->text_password);
        $containsUc = preg_match('/[A-Z]/', $this->text_password);
        $containsDigit = preg_match('/\d/', $this->text_password);
        $containsSpecial = preg_match('/[^a-zA-Z\d]/', $this->text_password);

        if ($containsDigit and $containsLc and $containsUc and $containsSpecial) {
            return true;
        } else {
            return false;
        }
    }

    protected function passwordDigitsValidation()
    {
        $total = preg_match_all("/[0-9]/", $this->text_password);

        if ($total > 5) {
            return true;
        } else {
            return false;
        }
    }


    public function isEqualTo(UserInterface $user)
    {
        if ($this->password !== $user->getPassword()) {
            
            return false;
        }
        
        if ($this->username !== $user->getUserIdentifier()) {
            return false;
        }

        return true;
    }


   

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(?string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(?string $lname): self
    {
        $this->lname = $lname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddedByAdmin(): ?bool
    {
        return $this->addedByAdmin;
    }

    public function setAddedByAdmin(?bool $addedByAdmin): self
    {
        $this->addedByAdmin = $addedByAdmin;

        return $this;
    }

    public function getRegisteredOnMobile(): ?bool
    {
        return $this->registeredOnMobile;
    }

    public function setRegisteredOnMobile(?bool $registeredOnMobile): self
    {
        $this->registeredOnMobile = $registeredOnMobile;

        return $this;
    }

    public function getIsGuest(): ?bool
    {
        return $this->isGuest;
    }

    public function setIsGuest(?bool $isGuest): self
    {
        $this->isGuest = $isGuest;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(?\DateTimeInterface $dob): self
    {
        $this->dob = $dob;

        return $this;
    }

    public function getIsLocked(): ?bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(?bool $isLocked): self
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGenderArray() {
        return $this->gender_array;
    }


    public function isEnabled(): ?bool
    {
        return ($this->isActive == 1);
    }

    public function isDisabled(): ?bool
    {
        return ($this->isActive == 2);
    }

    public function isDeleted(): ?bool
    {
        return ($this->isActive == 3);
    }

    public function getIsActive(): ?int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }



    public function getCreatorIp(): ?string
    {
        return $this->creator_ip;
    }

    public function setCreatorIp(?string $creator_ip): self
    {
        $this->creator_ip = $creator_ip;

        return $this;
    }

    public function getUpdatorIp(): ?string
    {
        return $this->updator_ip;
    }

    public function setUpdatorIp(?string $updator_ip): self
    {
        $this->updator_ip = $updator_ip;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getRegistrationConfirmationDate(): ?\DateTimeInterface
    {
        return $this->registrationConfirmationDate;
    }

    public function setRegistrationConfirmationDate(?\DateTimeInterface $registrationConfirmationDate): self
    {
        $this->registrationConfirmationDate = $registrationConfirmationDate;

        return $this;
    }

    public function getRegistrationType(): ?int
    {
        return $this->registrationType;
    }

    public function setRegistrationType(?int $registrationType): self
    {
        $this->registrationType = $registrationType;

        return $this;
    }

    public function getPasswordConfirmationDate(): ?\DateTimeInterface
    {
        return $this->passwordConfirmationDate;
    }

    public function setPasswordConfirmationDate(?\DateTimeInterface $passwordConfirmationDate): self
    {
        $this->passwordConfirmationDate = $passwordConfirmationDate;

        return $this;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public function setRoleId(?int $role_id): self
    {
        $this->role_id = $role_id;

        return $this;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function getFullName(): string
    {
        return $this->fname . " " . $this->lname;
    }

    /**
     * Set lastLogin
     * @ORM\PreUpdate
     * @param datetime $updateDate
     */
    public function setLastLogin()
    {
        $this->lastLogin = new \Datetime();
    }

    /**
     * @ORM\PreFlush()
     */
    public function handlePhoneNumber()
    {

        if ($this->getPhone()) {
            $this->setPhone(ltrim($this->getPhone(), "0"));
        }
    }

    public function getIndexrandomnumber(): ?int
    {
        return $this->indexrandomnumber;
    }

    public function setIndexrandomnumber(?int $indexrandomnumber): self
    {
        $this->indexrandomnumber = $indexrandomnumber;

        return $this;
    }

    public function getLoginCount(): ?int
    {
        return $this->loginCount;
    }

    public function setLoginCount(?int $loginCount): self
    {
        $this->loginCount = $loginCount;

        return $this;
    }
}
