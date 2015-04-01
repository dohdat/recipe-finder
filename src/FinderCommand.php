<?php
namespace Recipe_Finder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * FinderCommand
 * Setup the command using the Command component from Symfony
 *
 * @author Guillermo Gette <guilermogette@gmail.com>
 */

class FinderCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('find')
            ->setDescription('We will find out what can you cook tonight based on what you have your fridge.')
            ->addArgument('fridge_file', InputArgument::REQUIRED, 'Your fridge information in CSV format')
            ->addArgument('recipes_file', InputArgument::REQUIRED, 'A list of recipes in JSON format');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try{
            //load the fridge
            $fridge = new Fridge();
            $fridge->load($input->getArgument('fridge_file'));
            //get the possible recipes
            $recipes_list = Util::getJsonData($input->getArgument('recipes_file'));
        } catch(\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
            //big error
            return;
        }

        if (count($fridge->getItems()) == 0) {
            $output->writeln('Your fridge is empty! Order Takeout');
            //nothing else to do
            return;
        }

        $cookbook = $this->setUpCookbook($recipes_list);
        if (count($cookbook) == 0) {
            $output->writeln('No recipes, no suggestions! Order Takeout');
            //nothing else to do
            return;
        }

        //show any error while loading the fridge, we continue running anyways
        if (count($fridge->getLoadErrors()) > 0) {
            $errors = $fridge->getLoadErrors();
            foreach ($errors as $error) {
                $output->writeln('<comment>'.$error.'</comment>');
            }
        }

        $suggestions = array();
        foreach ($cookbook as $recipe) {
            //Recipe $recipe
            $ingredients = $recipe->getIngredients();
            $we_have_ingredients = true;
            foreach ($ingredients as $ingredient) {
                //Item $ingredient
                if (!$fridge->has($ingredient->getName(), $ingredient->getAmount())) {
                    //we dont have / expired / not enough  ingredient in the fridge
                    //lets go to the next recipe
                    $we_have_ingredients = false;
                    break;
                }
            }
            if ($we_have_ingredients) {
                $suggestions[] = $recipe;
            }
            
        }

        if (count($suggestions) == 0){
            //no suggestions
            $output->writeln('Order Takeout');
            return;
        }

        if (count($suggestions) == 1){
            //no suggestions
            $recipe = $suggestions[0];
            $output->writeln('You can prepare '.$recipe->getName());
            return;
        }

        //we have more than 1 suggesion so we need to pick one by expiration of the ingredients
        $recipe = $this->getTopSuggestion($suggestions, $fridge_items);
        $output->writeln('You can prepare '.$recipe->getName());
        return;
    }

    /**
     * Setup an array of Recipe(s)
     *
     * @param array $recipe_list the json to array parsed from the file
     * @return array of Recipe
     */
    protected function setUpCookbook($recipes_list)
    {
        $recipes = array();
        foreach ($recipes_list as $num => $recipe_data) {
            try{
                $recipe = new Recipe();
                $recipe->setName($recipe_data['name']);
                if (!isset($recipe_data['ingredients']) || count($recipe_data['ingredients']) == 0) {
                    $output->writeln("<comment>Ignoring recipe {$num}: No ingredients </comment>");
                }
                foreach ($recipe_data['ingredients'] as $i => $ingredient) {
                    $recipe->addIngredient($ingredient['item'], $ingredient['amount'], $ingredient['unit']);
                }
                $recipes[] = $recipe;
            } catch(\Exception $e) {
                $output->writeln("<comment>Ignoring recipe {$num}: ".$e->getMessage()."</comment>");
            }
        }
        return $recipes;
    }

    /**
     * If there are more than one suggestion here is the logic to pick one
     *
     * @param array $suggestion array of Recipe
     * @param array $fridge_items array of Item
     * @return Recipe
     */
    protected function getTopSuggestion($suggestions, $fridge_items)
    {
        $necessary_ingredients = array();
        foreach ($suggestions as $recipe_index => $recipe) {
            $ingredients = $recipe->getIngredients();
            foreach ($ingredients as $ingredient) {
                $expiration = $fridge_items[Fridge::itemHash($ingredient->getName())]->getExpiration();
                $necessary_ingredients[$expiration][] = $recipe_index;
            }
        }
        ksort($necessary_ingredients);
        $top_recipes = reset($necessary_ingredients);

        if (count($top_recipes) == 1) {
            return $suggestions[$top_recipes[0]];
        } else {
            //we have 2 or more recipes with the same ingredient
            $possible_indexes = count($top_recipes)-1;
            return $suggestions[$top_recipes[rand(0, $possible_indexes)]];
        }
    }
}