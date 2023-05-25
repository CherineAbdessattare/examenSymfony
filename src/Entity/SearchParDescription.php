<?php
namespace App\Entity;
class SearchParDescription
{
    private $description;
    public function getDescription(): ?string
    {
    return $this->description;
    }
    public function setDescription(string $description): self
    {
    $this->description = $description;
    return $this;
    }
}