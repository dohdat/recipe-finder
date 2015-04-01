<?php
use Recipe_Finder\FinderCommand;
use Recipe_Finder\Util;
use Recipe_Finder\Recipe;
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
        $recipe->setName('grilledcheeseontoast');
        $recipe->addIngredient('bread', '2', 'slices');
        $data = array($recipe);
        
        $this->assertEquals($data, $setUpCookbook->invokeArgs(new FinderCommand, array($recipe_list)));
    }
}