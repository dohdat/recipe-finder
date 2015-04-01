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
            $recipes = Util::getJsonData($input->getArgument('recipes_file'));
        } catch(\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
            return;
        }
        $output->writeln('all good');
    }
}