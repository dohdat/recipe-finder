<?php
use Recipe_Finder\FinderCommand;
use Recipe_Finder\Util;
use Recipe_Finder\Recipe;
use Recipe_Finder\Item;
use Recipe_Finder\Fridge;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class FinderCommandTest extends \PHPUnit_Framework_TestCase
{
    function testSetUpCookbook()
    {
        //set up cookbook method to be accessible as it is private
        $recipe_list = Util::getJsonData('tests/recipes.json');
        $setUpCookbook = new \ReflectionMethod('Recipe_Finder\FinderCommand','setUpCookbook');
        $setUpCookbook->setAccessible(true);
        
        $recipe = new Recipe();
        $recipe->setName('grilled cheese on toast');
        $recipe->addIngredient('bread', '2', 'slices');
        $data = array($recipe);

        $this->assertEquals($data, $setUpCookbook->invokeArgs(new FinderCommand, array($recipe_list)));
    }

    function testGetTopSuggestion()
    {
        $getTopSuggestion = new \ReflectionMethod('Recipe_Finder\FinderCommand','getTopSuggestion');
        $getTopSuggestion->setAccessible(true);

        $recipe1 = new Recipe();
        $recipe1->setName('recipe 1');
        $recipe1->addIngredient('bread', '2', 'slices');

        $recipe2 = new Recipe();
        $recipe2->setName('recipe 2');
        $recipe2->addIngredient('cheese', '2', 'slices');

        $recipe3 = new Recipe();
        $recipe3->setName('recipe 3');
        $recipe3->addIngredient('milk', '200', 'ml');

        $suggestions = array($recipe1, $recipe2, $recipe3);

        $item1 = new Item();
        $item1->setName('bread');
        $item1->setExpiration('1/6/2015');

        $item2 = new Item();
        $item2->setName('cheese');
        $item2->setExpiration('2/6/2015');

        $item3 = new Item();
        $item3->setName('milk');
        $item3->setExpiration('3/6/2015');

        $fridge_items = array(
            Fridge::itemHash($item1->getName()) => $item1,
            Fridge::itemHash($item2->getName()) => $item2,
            Fridge::itemHash($item3->getName()) => $item3
        );
        //test the closest expiration date
        $this->assertEquals($recipe1, $getTopSuggestion->invokeArgs(new FinderCommand, array($suggestions, $fridge_items)));

        //with 2 recipes with ingredientes in the same expiration date should always pick one 
        $item2->setExpiration('1/6/2015');
        $this->assertInstanceOf('Recipe_Finder\Recipe',$getTopSuggestion->invokeArgs(new FinderCommand, array($suggestions, $fridge_items)));
    }

    public function testRightRecipe()
    {
        $application = new Application();
        $application->add(new FinderCommand());

        $command = $application->find('find');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'fridge_file' => dirname(__DIR__).'/fridge.csv',
            'recipes_file' => dirname(__DIR__).'/recipes.json',
        ));

        $this->assertRegExp('/You can prepare salad sandwich/', $commandTester->getDisplay());
    }
}