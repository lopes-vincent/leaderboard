<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
        operations: [
            new Get(),
            new GetCollection()
        ],
        normalizationContext: ['groups' => [self::GROUP_READ]],
        denormalizationContext: ['groups' => [self::GROUP_WRITE]]
    )
]
class Game
{
    const GROUP_READ = "game.read";
    const GROUP_READ_SINGLE = "game.read_single";
    const GROUP_WRITE = "game.write";
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups([self::GROUP_WRITE, self::GROUP_READ])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_WRITE, self::GROUP_READ])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Score::class, orphanRemoval: true)]
    #[Groups(self::GROUP_READ_SINGLE)]
    private Collection $scores;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiKey = null;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setGame($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getGame() === $this) {
                $score->setGame(null);
            }
        }

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
