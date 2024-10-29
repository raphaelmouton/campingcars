<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
#[Vich\Uploadable]
class Annonces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $TypeVehicule = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?User $User = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Creation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
    
    #[ORM\Column]
    private ?bool $ACTIVE = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;

    #[ORM\Column(length: 255)]
    private ?string $SlugTitre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?int $Prix = null;

    #[ORM\Column(length: 255)]
    private ?string $Etat = null;

    #[ORM\Column]
    private ?int $KM = null;

    #[ORM\Column(length: 255)]
    private ?string $DateCT = null;

    #[ORM\Column]
    private ?int $NbrCouchage = null;

    #[ORM\Column(length: 255)]
    private ?string $Region = null;

    #[ORM\Column(length: 255)]
    private ?string $Ville = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize1 = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName1 = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'annonces', fileNameProperty: 'imageName1', size: 'imageSize1')]
    private ?File $imageFile1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize2 = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName2 = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'annonces', fileNameProperty: 'imageName2', size: 'imageSize2')]
    private ?File $imageFile2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize3 = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName3 = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'annonces', fileNameProperty: 'imageName3', size: 'imageSize3')]
    private ?File $imageFile3 = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize4 = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName4 = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'annonces', fileNameProperty: 'imageName4', size: 'imageSize4')]
    private ?File $imageFile4 = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize5 = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName5 = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'annonces', fileNameProperty: 'imageName5', size: 'imageSize5')]
    private ?File $imageFile5 = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize6 = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName6 = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'annonces', fileNameProperty: 'imageName6', size: 'imageSize6')]
    private ?File $imageFile6 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $OptionsPayantes = null;

    #[ORM\Column(nullable: true)]
    private ?int $stripeCheckoutSessionId = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ReferencementPaymentOK = null;

    #[ORM\Column(nullable: true)]
    private ?bool $BoostPaymentOK = null;

    #[ORM\Column(nullable: true)]
    private ?bool $DuoPaymentOK = null;

    #[ORM\OneToMany(mappedBy: 'annonces', targetEntity: Message::class)]
    private Collection $Messages;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'Annonce', targetEntity: Conversation::class)]
    private Collection $conversations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeVehicule(): ?string
    {
        return $this->TypeVehicule;
    }

    public function setTypeVehicule(string $TypeVehicule): static
    {
        $this->TypeVehicule = $TypeVehicule;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->Creation;
    }

    public function setCreation(\DateTimeInterface $Creation): static
    {
        $this->Creation = $Creation;

        return $this;
    }

    public function getMAJ(): ?\DateTimeInterface
    {
        return $this->MAJ;
    }

    public function setMAJ(?\DateTimeInterface $MAJ): static
    {
        $this->MAJ = $MAJ;

        return $this;
    }

    public function isACTIVE(): ?bool
    {
        return $this->ACTIVE;
    }

    public function setACTIVE(bool $ACTIVE): static
    {
        $this->ACTIVE = $ACTIVE;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getSlugTitre(): ?string
    {
        return $this->SlugTitre;
    }

    public function setSlugTitre(string $SlugTitre): static
    {
        $this->SlugTitre = $SlugTitre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): static
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getKM(): ?int
    {
        return $this->KM;
    }

    public function setKM(int $KM): static
    {
        $this->KM = $KM;

        return $this;
    }

    public function getDateCT(): ?string
    {
        return $this->DateCT;
    }

    public function setDateCT(string $DateCT): static
    {
        $this->DateCT = $DateCT;

        return $this;
    }

    public function getNbrCouchage(): ?int
    {
        return $this->NbrCouchage;
    }

    public function setNbrCouchage(int $NbrCouchage): static
    {
        $this->NbrCouchage = $NbrCouchage;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->Region;
    }

    public function setRegion(string $Region): static
    {
        $this->Region = $Region;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): static
    {
        $this->Ville = $Ville;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile1(?File $imageFile1 = null): void
    {
        $this->imageFile1 = $imageFile1;

        if (null !== $imageFile1) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile1(): ?File
    {
        return $this->imageFile1;
    }

    public function setImageName1(?string $imageName1): void
    {
        $this->imageName1 = $imageName1;
    }

    public function getImageName1(): ?string
    {
        return $this->imageName1;
    }

    public function setImageSize1(?int $imageSize1): void
    {
        $this->imageSize1 = $imageSize1;
    }

    public function getImageSize1(): ?int
    {
        return $this->imageSize1;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile2(?File $imageFile2 = null): void
    {
        $this->imageFile2 = $imageFile2;

        if (null !== $imageFile2) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile2(): ?File
    {
        return $this->imageFile2;
    }

    public function setImageName2(?string $imageName2): void
    {
        $this->imageName2 = $imageName2;
    }

    public function getImageName2(): ?string
    {
        return $this->imageName2;
    }

    public function setImageSize2(?int $imageSize2): void
    {
        $this->imageSize2 = $imageSize2;
    }

    public function getImageSize2(): ?int
    {
        return $this->imageSize2;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
    
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile3(?File $imageFile3 = null): void
    {
        $this->imageFile3 = $imageFile3;

        if (null !== $imageFile3) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile3(): ?File
    {
        return $this->imageFile3;
    }

    public function setImageName3(?string $imageName3): void
    {
        $this->imageName3 = $imageName3;
    }

    public function getImageName3(): ?string
    {
        return $this->imageName3;
    }

    public function setImageSize3(?int $imageSize3): void
    {
        $this->imageSize3 = $imageSize3;
    }

    public function getImageSize3(): ?int
    {
        return $this->imageSize3;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile4(?File $imageFile4 = null): void
    {
        $this->imageFile4 = $imageFile4;

        if (null !== $imageFile4) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile4(): ?File
    {
        return $this->imageFile4;
    }

    public function setImageName4(?string $imageName4): void
    {
        $this->imageName4 = $imageName4;
    }

    public function getImageName4(): ?string
    {
        return $this->imageName4;
    }

    public function setImageSize4(?int $imageSize4): void
    {
        $this->imageSize4 = $imageSize4;
    }

    public function getImageSize4(): ?int
    {
        return $this->imageSize4;
    }

                /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile5(?File $imageFile5 = null): void
    {
        $this->imageFile5 = $imageFile5;

        if (null !== $imageFile5) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile5(): ?File
    {
        return $this->imageFile5;
    }

    public function setImageName5(?string $imageName5): void
    {
        $this->imageName5 = $imageName5;
    }

    public function getImageName5(): ?string
    {
        return $this->imageName5;
    }

    public function setImageSize5(?int $imageSize5): void
    {
        $this->imageSize5 = $imageSize5;
    }

    public function getImageSize5(): ?int
    {
        return $this->imageSize5;
    }

                /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile6(?File $imageFile6 = null): void
    {
        $this->imageFile6 = $imageFile6;

        if (null !== $imageFile6) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile6(): ?File
    {
        return $this->imageFile6;
    }

    public function setImageName6(?string $imageName6): void
    {
        $this->imageName6 = $imageName6;
    }

    public function getImageName6(): ?string
    {
        return $this->imageName6;
    }

    public function setImageSize6(?int $imageSize6): void
    {
        $this->imageSize6 = $imageSize6;
    }

    public function getImageSize6(): ?int
    {
        return $this->imageSize6;
    }

    public function getOptionsPayantes(): ?string
    {
        return $this->OptionsPayantes;
    }

    public function setOptionsPayantes(?string $OptionsPayantes): static
    {
        $this->OptionsPayantes = $OptionsPayantes;

        return $this;
    }

    public function getStripeCheckoutSessionId(): ?int
    {
        return $this->stripeCheckoutSessionId;
    }

    public function setStripeCheckoutSessionId(?int $stripeCheckoutSessionId): static
    {
        $this->stripeCheckoutSessionId = $stripeCheckoutSessionId;

        return $this;
    }

    public function isReferencementPaymentOK(): ?bool
    {
        return $this->ReferencementPaymentOK;
    }

    public function setReferencementPaymentOK(?bool $ReferencementPaymentOK): static
    {
        $this->ReferencementPaymentOK = $ReferencementPaymentOK;

        return $this;
    }

    public function isBoostPaymentOK(): ?bool
    {
        return $this->BoostPaymentOK;
    }

    public function setBoostPaymentOK(?bool $BoostPaymentOK): static
    {
        $this->BoostPaymentOK = $BoostPaymentOK;

        return $this;
    }

    public function isDuoPaymentOK(): ?bool
    {
        return $this->DuoPaymentOK;
    }

    public function setDuoPaymentOK(?bool $DuoPaymentOK): static
    {
        $this->DuoPaymentOK = $DuoPaymentOK;

        return $this;
    }

        /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->Messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->Messages->contains($message)) {
            $this->Messages->add($message);
            $message->setAnnonces($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->Messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAnnonces() === $this) {
                $message->setAnnonces(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setAnnonce($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getAnnonce() === $this) {
                $conversation->setAnnonce(null);
            }
        }

        return $this;
    }

}
