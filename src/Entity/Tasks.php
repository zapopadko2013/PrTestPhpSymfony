<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    
    
	
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[Column(type: "string", length: 255)]
    private string $name;

    #[Column(type: "string", length: 255, nullable: true)]
    private ?string $desription;

    #[Column(type: "string", nullable: true)]
    private ?string $status;

    

   public function getId()
    {
        return $this->id;
    }

    

    

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDesription(): ?string
    {
        return $this->desription;
    }

    /**
     * @param string $desription
     */
     public function setDescription(?string $desription): self
    {
        $this->desription = $desription;

        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return Post
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }


    

}
