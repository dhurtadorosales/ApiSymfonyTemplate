<?php

namespace UserBundle\Entity;

use AppBundle\Entity\Alias;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 *
 * @UniqueEntity(
 *     fields={"email"},
 *     message="message.email.already_used"
 * )
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="message.required")
     * @Assert\Regex(
     *     pattern="/^[A-Z a-zÑñáéíóúÁÉÍÓÚ , .]*$/",
     *     message="message.regex.string"
     * )
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="message.required")
     * @Assert\Regex(
     *     pattern="/^[A-Z a-zÑñáéíóúÁÉÍÓÚ , .]*$/",
     *     message="message.regex.string"
     * )
     */
    protected $lastName;

    /**
     * @var Alias[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Alias", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $alias;

    /**
     * @var Client
     *
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Client", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->alias = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Add alias
     *
     * @param \AppBundle\Entity\Alias $alias
     *
     * @return User
     */
    public function addAlias(\AppBundle\Entity\Alias $alias)
    {
        $this->alias[] = $alias;

        return $this;
    }

    /**
     * Remove alias
     *
     * @param \AppBundle\Entity\Alias $alias
     */
    public function removeAlias(\AppBundle\Entity\Alias $alias)
    {
        $this->alias->removeElement($alias);
    }

    /**
     * Get alias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set client
     *
     * @param Client $client
     *
     * @return User
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() . ' ' . $this->getLastName();
    }

}
