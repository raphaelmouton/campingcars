<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    
    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $User = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $Contenu = null;

    #[ORM\ManyToOne(inversedBy: 'messages', cascade: ["persist"])]
    private ?Conversation $conversation = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Annonces $annonce = null;

    #[ORM\Column(length: 255)]
    private ?string $Envoyeur = null;

    #[ORM\Column(length: 255)]
    private ?string $Receveur = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $PosteLe = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(?string $Contenu): self
    {
        $this->Contenu = $Contenu;

        return $this;
    }

    public function getEnvoyeur(): ?string
    {
        return $this->Envoyeur;
    }

    public function setEnvoyeur($Envoyeur): self
    {
        $this->Envoyeur = $Envoyeur;

        return $this;
    }

    public function getReceveur(): ?string
    {
        return $this->Receveur;
    }

    public function setReceveur($Receveur): self
    {
        $this->Receveur = $Receveur;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getAnnonce(): ?Annonces
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonces $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastMessage(): ?Message
    {
        return $this->conversation ? $this->conversation->getLastMessage() : $this;
    }

    public function getPosteLe(): ?\DateTimeInterface
    {
        return $this->PosteLe;
    }

    public function setPosteLe(\DateTimeInterface $PosteLe): self
    {
        $this->PosteLe = $PosteLe;

        return $this;
    }
}
