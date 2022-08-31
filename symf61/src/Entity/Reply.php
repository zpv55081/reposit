<?php

namespace App\Entity;

use App\Repository\ReplyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReplyRepository::class)]
class Reply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $user_ipv4 = null;

    #[ORM\Column]
    private ?int $question_id = null;

    #[ORM\Column]
    private ?int $variant_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIpv4(): ?string
    {
        return $this->user_ipv4;
    }

    public function setUserIpv4(?string $user_ipv4): self
    {
        $this->user_ipv4 = $user_ipv4;

        return $this;
    }

    public function getQuestionId(): ?int
    {
        return $this->question_id;
    }

    public function setQuestionId(int $question_id): self
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getVariantId(): ?int
    {
        return $this->variant_id;
    }

    public function setVariantId(int $variant_id): self
    {
        $this->variant_id = $variant_id;

        return $this;
    }
}
