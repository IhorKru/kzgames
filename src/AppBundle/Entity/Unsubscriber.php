<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Unsubscriber
 *
 * @ORM\Table(name="unsubscriber")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnsubscriberRepository")
 */
class Unsubscriber
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * 
     * @Assert\NotBlank(message="Fill in Email field")
     * @ORM\Column(name="email_address", type="string", length=100)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true,
     *     checkHost = true
     * )
     */
    private $emailaddress;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set emailaddress
     *
     * @param string $emailaddress
     *
     * @return Unsubscriber
     */
    public function setEmailaddress($emailaddress)
    {
        $this->emailaddress = $emailaddress;

        return $this;
    }

    /**
     * Get emailaddress
     *
     * @return string
     */
    public function getEmailaddress()
    {
        return $this->emailaddress;
    }
}

