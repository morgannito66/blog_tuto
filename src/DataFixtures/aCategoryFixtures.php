<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class aCategoryFixtures extends Fixture
{

  public const CAT_HTML = 'default';

  public function load(ObjectManager $manager)
  {

      $categoryHTML = new Category();
      $categoryHTML->setName("HTML");
      $manager->persist($categoryHTML);

      $categoryCSS = new Category();
      $categoryCSS->setName("CSS");
      $manager->persist($categoryCSS);

      $categoryJS = new Category();
      $categoryJS->setName("JS");
      $manager->persist($categoryJS);

      $categorySYMF = new Category();
      $categorySYMF->setName("SYMFONY 4");
      $manager->persist($categorySYMF);

      $categoryC = new Category();
      $categoryC->setName("C#");
      $manager->persist($categoryC);

      // other fixtures can get this object using the CategoryFixtures::CONSTANT
      $this->addReference(self::CAT_HTML, $categoryHTML);

      $manager->flush();
  }
}
