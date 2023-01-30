<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Messenger
 *
 * @ORM\Table(name="messenger", indexes={@ORM\Index(name="id_users", columns={"id_users"})})
 * @ORM\Entity
 */
class Messenger
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
     * @ORM\Column(name="email_from", type="string", length=25, nullable=false)
     */
    private $emailFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="destinatary", type="string", length=25, nullable=false)
     */
    private $destinatary;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=50, nullable=false)
     */
    private $message;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=false)
     */
    private $time;


    /**
     * @var Boolean
     *
     * @ORM\Column(name="isChecked", type="boolean", nullable=true, options={"default": false })
     */
    private $isChecked;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="subject",type="string",length=25,nullable=false)
     */
    private $subject;



    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_users", referencedColumnName="id")
     * })
     */
    private $idUsers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailFrom(): ?string
    {
        return $this->emailFrom;
    }

    public function setEmailFrom(string $emailFrom): self
    {
        $this->emailFrom = $emailFrom;

        return $this;
    }

    public function getDestinatary(): ?string
    {
        return $this->destinatary;
    }

    public function setDestinatary(string $destinatary): self
    {
        $this->destinatary = $destinatary;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }



    public function getIdUsers() #: ?User
    {
        return $this->idUsers;
    }

    public function setIdUsers(?User $idUsers)
    {
        return  $this->idUsers = $idUsers;
    }


    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return self
     */
    public function setTime(String $hola)#si o si tiene que recibir un parametro por lo que me lo invento para evitar errores
    {
        #crear fecha actual para introducirla en la bdd
        $date = new \DateTime();
        $date->format('Y-m-d H:i:s');

        $this->time = $date;
        return $this;
    }

    /**
     * Get isChecked
     *
     * @return boolean|null
     */
    public function getIsChecked()
    {
        return $this->isChecked;
    }


    public function setIsChecked($isChecked)
    {
        $this->isChecked = $isChecked;
        return $this;
    }


    public function setSubject(string $subject)
    {

        return $this->subject = $subject;
    }

    public function getSubject()
    {

        return $this->subject;;
    }
}
