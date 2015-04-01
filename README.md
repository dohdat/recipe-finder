# recipe-finder
By providing a CSV file with the items in your fridge and a list of recipes we can suggest you what can you cook tonight. Check out the examples files in the repository.

## Requirements
- PHP > 5.3.3

## Installation
1. $ git clone git@github.com:guillegette/recipe-finder.git
2. $ cd recipe-finder
3. $ curl -sS https://getcomposer.org/installer | php (https://getcomposer.org/download/)
4. $ php composer.phar install
5. Run the test $ vendor/bin/phpunit --bootstrap vendor/autoload.php tests/

## How to use it
$ php recipe-finder find path/to/fridge.csv path/to/recipes.json

## Help?
$ php recipe-finder help 

## Assumptions (edge cases)
- An item with no expiration it will never expires
- If the item is repeated (by name) in the fridge.csv we increase the amount
- If there is a recipe with no ingredients we ignore it
- If 2 recipes share the item with the closest expiration date we pick one using rand()
