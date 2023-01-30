<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


/**

 * User

 *

 * @ORM\Table(name="user")

 * @ORM\Entity

 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface

{
    /**

     * @var int

     *

     * @ORM\Column(name="id", type="integer", nullable=false)

     * @ORM\Id

     * @ORM\GeneratedValue(strategy="IDENTITY")

     */
    private $id;



    /**

     * @var string

     *

     * @ORM\Column(name="name", type="string", length=25, nullable=false)

     */

    private $name;



    /**

     * @var string

     *

     * @ORM\Column(name="email", type="string", length=25, nullable=false)

     */

    private $email;



    /**

     * @var string

     *

     * @ORM\Column(name="pass", type="string", length=25, nullable=false)

     */

    private $password;



    public function getId(): ?int

    {
        return $this->id;
    }


    public function getName(): ?string
    {

        return $this->name;
    }



    public function setName(string $name): self

    {

        $this->name = $name;
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



    public function getPassword(): ?string

    {

        return $this->password;
    }



    public function setPassword(string $password): self

    {

        $this->password = $password;

        return $this;
    }



    /**

     * @ORM\OneToMany(targetEntity="App\Entity\Messenger", mappedBy="idUsers")

     */

    private $messages;



    public function __construct()

    {

        $this->messages = new ArrayCollection();
    }


    /**

     * @return Collection|messages[]

     */



    public function getMessages()

    {

        return $this->messages;
    }


    #funciones necesarias para implentar UserInterface



    public function getUserIdentifier(): string

    {

        return (string) $this->email;
    }



    public function getSalt()

    {

        return null;
    }



    public function eraseCredentials()

    {
    }



    public function getRoles(): array

    {

        return array('ROLE_USER');
    }
}
