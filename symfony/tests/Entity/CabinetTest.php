<?php

namespace App\Tests\Entity;

use App\Entity\Cabinet;
use App\Entity\Orthophoniste;
use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class CabinetTest extends TestCase
{
    public function testSomething(): void
    {
        $cabinet = new Cabinet();
        $cabinet->setNom('Cabinet Central')
            ->setAdresse('123 Rue Principale')
            ->setTelephone('0123456789');

        $this->assertSame('Cabinet Central', $cabinet->getNom());
        $this->assertSame('123 Rue Principale', $cabinet->getAdresse());
        $this->assertSame('0123456789', $cabinet->getTelephone());
    }

    public function testCollectionsAreEmptyOnInit(): void
    {
        $cabinet = new Cabinet();

        $this->assertCount(0, $cabinet->getOrthophonistes());
        $this->assertCount(0, $cabinet->getImagesCabinet());
    }

    public function testAddAndRemoveOrthophoniste(): void
    {
        $orthophoniste = new Orthophoniste();
        $cabinet = new Cabinet();

        $cabinet->addOrthophoniste($orthophoniste);
        $this->assertCount(1, $cabinet->getOrthophonistes());
        $this->assertSame($cabinet, $orthophoniste->getCabinet());

        $cabinet->removeOrthophoniste($orthophoniste);
        $this->assertCount(0, $cabinet->getOrthophonistes());
        $this->assertNull($orthophoniste->getCabinet());
    }

    public function testAddAndRemoveImage(): void
    {
        $cabinet = new Cabinet();
        $image = new Image();

        $cabinet->addImagesCabinet($image);

        $this->assertCount(1, $cabinet->getImagesCabinet());
        $this->assertSame($cabinet, $image->getCabinet());

        $cabinet->removeImagesCabinet($image);

        $this->assertCount(0, $cabinet->getImagesCabinet());
        $this->assertNull($image->getCabinet());
    }
}
