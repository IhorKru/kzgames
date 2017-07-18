<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\SubscriberOptInDetails;

/**
 * SubscriberDetails
 *
 * @ORM\Table(name="01_SubscriberDetails", uniqueConstraints={@ORM\UniqueConstraint(name="subsc_details_pkey", columns={"id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubscriberDetailsRepository")
 */
class SubscriberDetails
{
    
    /**
     *@ORM\OneToMany(targetEntity="SubscriberOptInDetails", mappedBy="user", cascade={"persist"})
     */
    private $optindetails;
            
    public function __construct()
    {
        $this->optindetails = new ArrayCollection();
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * 
     */
    private $id;


    /**
     * @var string
     *
     * @Assert\NotBlank (message="Введите кореектный номер мобильного")
     * @ORM\Column(name="phone", type="string", length=50)
     * @Assert\Length(min=5) (message="Мобильный номер должен состоять из 9 цифр")
     */
    private $phone;

    /**
     * @var string
     *
     * @Assert\NotBlank (message="Введите првельный SMS код")
     * @ORM\Column(name="phone", type="string", length=50)
     * @Assert\Length(min=5) (message="SMS код должен состоять из 7 символов")
     */
    private $smscode;

    /**
     * @var int
     *
     * @ORM\Column(name="sourceid", type="smallint")
     */
    private $sourceid;

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return SubscriberDetails
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return SubscriberDetails
     */
    public function setPhone($phone)
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

    /**
     * Set smscode
     *
     * @param string $smscode
     *
     * @return SubscriberDetails
     */
    public function setSmscode($smscode)
    {
        $this->smscode = $smscode;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getSmscode()
    {
        return $this->smscode;
    }

    /**
     * Set sourceid
     *
     * @param integer $sourceid
     *
     * @return SubscriberDetails
     */
    public function setSourceid($sourceid)
    {
        $this->sourceid = $sourceid;

        return $this;
    }

    /**
     * Get sourceid
     *
     * @return integer
     */
    public function getSourceid()
    {
        return $this->sourceid;
    }

}
