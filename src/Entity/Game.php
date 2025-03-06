<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\Provider\GameExtensionsProvider;

// Todo : Get specific collections (last updated, top rated etc...)

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Get(
            name: 'getExtensions',
            uriTemplate: '/games/{id}/extensions',
            normalizationContext: ['groups' => ['game:read','extension:read']],
            provider: GameExtensionsProvider::class
        )
    ]
)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('game:read')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups('game:read')]
    private ?int $steamId = null;

    #[ORM\Column(nullable: true)]
    #[Groups('game:read')]
    private ?int $apiId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('game:read', 'extension:read')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('game:read')]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('game:read')]
    private ?string $imageUrl = null;

    #[ORM\Column(nullable: true)]
    #[Groups('game:read')]
    private ?\DateTimeImmutable $releasedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups('game:read')]
    private ?\DateTimeImmutable $lastUpdatedAt = null;

    /**
     * @var Collection<int, Extension>
     */
    #[ORM\OneToMany(targetEntity: Extension::class, mappedBy: 'game', orphanRemoval: true)]
    #[Groups('game:read', 'extension:read')]
    private Collection $extensions;

    public function __construct()
    {
        $this->extensions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSteamId(): ?int
    {
        return $this->steamId;
    }

    public function setSteamId(?int $steamId): static
    {
        $this->steamId = $steamId;

        return $this;
    }

    public function getApiId(): ?int
    {
        return $this->apiId;
    }

    public function setApiId(?int $apiId): static
    {
        $this->apiId = $apiId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeImmutable
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(?\DateTimeImmutable $releasedAt): static
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    public function getLastUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(?\DateTimeImmutable $lastUpdatedAt): static
    {
        $this->lastUpdatedAt = $lastUpdatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Extension>
     */
    public function getExtensions(): Collection
    {
        return $this->extensions;
    }

    public function addExtension(Extension $extension): static
    {
        if (!$this->extensions->contains($extension)) {
            $this->extensions->add($extension);
            $extension->setGame($this);
        }

        return $this;
    }

    public function removeExtension(Extension $extension): static
    {
        if ($this->extensions->removeElement($extension)) {
            // set the owning side to null (unless already changed)
            if ($extension->getGame() === $this) {
                $extension->setGame(null);
            }
        }

        return $this;
    }
}
