<?php
namespace Recipe_Finder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
            return;
        }

        if (count($frige->getItems()) == 0) {
            $output->writeln('Your fridge is empty! Order take out');
            return;
        }
        if (count($recipes_list) == 0) {
            $output->writeln('No recipes, no suggestions! Order take out');
            return;
        }

        //show any error while loading the fridge
        if (count($fridge->getLoadErrors()) > 0) {
            $errors = $fridge->getLoadErrors();
            foreach ($errors as $error) {
                $output->writeln('<comment>'.$error.'</comment>');
            }
        }

        $output->writeln('all good');
    }

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
}