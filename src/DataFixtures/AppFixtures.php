<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 50;

    private const CATEGORIES_NAMES = ["Sport", "France", "International", "Économie", "Politique"];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('zh_TW');

        $categoryNameIdx = 0;

        $populator = new Populator($faker, $manager);
        $populator->addEntity(Category::class, count(self::CATEGORIES_NAMES), [
            'name' => function () use (&$categoryNameIdx) {
                return self::CATEGORIES_NAMES[$categoryNameIdx++];
            }
        ]);
        $populator->addEntity(Article::class, self::NB_ARTICLES, [
            'title' => function () use ($faker) {
                return $faker->realTextBetween(9, 15);
            },
            'content' => function () use ($faker) {
                return $faker->realTextBetween(150, 350);
            },
            'updatedAt' => null
        ]);
        $populator->execute();
    }
}
