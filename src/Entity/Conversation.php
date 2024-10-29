<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $sujet = null;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'conversations')]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'conversations')]
    private ?Annonces $annonce = null;

    #[ORM\Column(length: 255)]
    private ?string $Envoyeur = null;

    #[ORM\Column(length: 255)]
    private ?string $Receveur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $VuParEnvoyeur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $VuParReceveur = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable(); // Initialisation de la date de création
        $this->messages = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

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

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

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

    public function isVuParEnvoyeur(): ?bool
    {
        return $this->VuParEnvoyeur;
    }

    public function setVuParEnvoyeur(?bool $VuParEnvoyeur): self
    {
        $this->VuParEnvoyeur = $VuParEnvoyeur;

        return $this;
    }

    public function isVuParReceveur(): ?bool
    {
        return $this->VuParReceveur;
    }

    public function setVuParReceveur(?bool $VuParReceveur): self
    {
        $this->VuParReceveur = $VuParReceveur;

        return $this;
    }
}
