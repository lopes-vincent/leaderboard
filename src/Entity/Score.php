<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Controller\SubmitScoreController;
use App\Repository\ScoreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
#[ApiResource(
        operations: [
            new GetCollection(),
            new Post(
                uriTemplate: '/scores/{gameCode}',
                uriVariables: [
                    'gameCode' => new Link(
                        fromProperty: 'code',
                        fromClass: Game::class,
                    )
                ], 
                controller: SubmitScoreController::class,
                read:false
            )        
        ],
        normalizationContext: [
            'groups' => [self::GROUP_READ],
            'datetime_format' => "Y-m-d H:i"
        ],
        denormalizationContext: ['groups' => [self::GROUP_WRITE]],
        order: ['score' => 'ASC'],
        paginationItemsPerPage: 10,
        paginationMaximumItemsPerPage: 10
    )
]
#[ApiFilter(SearchFilter::class, properties: ['game.code'])]
class Score
{
    use TimestampableEntity;

    const GROUP_READ = "score.read";
    const GROUP_READ_SINGLE = "score.read_single";
    const GROUP_WRITE = "score.write";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_WRITE, self::GROUP_READ])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups([self::GROUP_WRITE, self::GROUP_READ])]
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column(type: Types::GUID, unique: true)]
    #[Groups([self::GROUP_WRITE])]
    private ?string $uuid = null;

    #[Groups([self::GROUP_WRITE])]
    private ?string $hash = null;
    
    #[Groups([self::GROUP_READ])]
    private int $position;

    #[Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([self::GROUP_READ])]
    protected $createdAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;

        return $this;
    }
    
    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
