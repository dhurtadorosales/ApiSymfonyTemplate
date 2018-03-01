<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Alias
 *
 * @ORM\Table(name="alias")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AliasRepository")
 *
 * @UniqueEntity(
 *     fields={"name"},
 *     message="message.alias.already_used"
 * )
 */
class Alias
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
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank(message="message.required")
     * @Assert\Regex(
     *     pattern="/^[A-Z a-zÑñáéíóúÁÉÍÓÚ , .]*$/",
     *     message="message.regex.string"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="text")
     *
     * @Assert\NotBlank(message="message.required")
     * @Assert\Regex(
     *     pattern="/^[A-Z a-zÑñáéíóúÁÉÍÓÚ , .]*$/",
     *     message="message.regex.string"
     * )
     */
    private $origin;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="alias")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

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
     * @return Alias
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
     * Set origin
     *
     * @param string $origin
     *
     * @return Alias
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Alias
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
