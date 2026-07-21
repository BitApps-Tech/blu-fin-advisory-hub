<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompleteMenuSeeder extends Seeder
{
    public function run()
    {
        // Helper function to extract price from "XXX ETB" format
        $extractPrice = function($priceString) {
            $priceMatch = preg_match('/[\d,]+\.?\d*/', $priceString, $matches);
            if ($priceMatch && isset($matches[0])) {
                return (float) str_replace(',', '', $matches[0]);
            }
            return 0.00;
        };

        // Create categories
        $categories = [
            ['id' => 'breakfast', 'name' => 'Breakfast', 'order' => 1],
            ['id' => 'lunch-dinner', 'name' => 'Lunch+ Dinner', 'order' => 2],
            ['id' => 'hot-drink', 'name' => 'Hot Drink', 'order' => 3],
            ['id' => 'spirits', 'name' => 'Spirits', 'order' => 4],
            ['id' => 'cocktail-shot', 'name' => 'Cocktail Shot', 'order' => 5],
            ['id' => 'wine', 'name' => 'Wine', 'order' => 6],
            ['id' => 'juice', 'name' => 'Juice', 'order' => 7],
            ['id' => 'pastry', 'name' => 'Pastry', 'order' => 8],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $category = MenuCategory::updateOrCreate(
                ['slug' => $cat['id']],
                [
                    'name' => $cat['name'],
                    'slug' => $cat['id'],
                    'description' => "Delicious {$cat['name']} items",
                    'is_active' => true,
                    'order' => $cat['order'],
                ]
            );
            $categoryMap[$cat['id']] = $category->id;
        }

        // Breakfast items
        $breakfastItems = [
            ['name' => 'Custard crepes with fruits and caramel sauce', 'description' => 'Crepes batter, fruits, custard cream, caramel sauce', 'price' => '600 ETB', 'popular' => true],
            ['name' => 'Avocado sourdough Toast', 'description' => 'Avocado Mashed, sourdough bread, Pickled onion, pickled Cucumber, sundried tomato, Seseme seed, olive oil, egg (you can choose the cooking)', 'price' => '500 ETB', 'popular' => false],
            ['name' => 'Hummus sourdough toast', 'description' => 'Hummus, sourdough bread, Pickled Cucumber, Pickled onion, sundried tomato, seseme seed, olive oil, egg (you can choose the cooking)', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Sourdough Breakfast sandwich/omelet/ Scrambled egg', 'description' => 'Sourdough, egg, house mayonnaise, provolone cheese, Avocado, Spinach', 'price' => '750 ETB', 'popular' => false],
            ['name' => 'Croque madam', 'description' => 'Broish bread, Beef slice, provolone cheese, béchamel sauce, egg', 'price' => '600 ETB', 'popular' => false],
            ['name' => 'Croque Monisior', 'description' => 'Broish bread, Beef slice, provolone cheese, béchamel sauce', 'price' => '600 ETB', 'popular' => false],
            ['name' => 'Toasted bagel with smoked Salmon', 'description' => 'Bagel bread, Philadelphia Cream cheese, smoked Salmon, Pickled Cucumber, Pickled onion', 'price' => '1980 ETB', 'popular' => true],
            ['name' => 'Steak and eggs with sourdough bread', 'description' => 'Steak meat, Egg, sourdough bread', 'price' => '1500 ETB', 'popular' => false],
            ['name' => 'Stuffed Crossant with side fruit/salad', 'description' => 'Plan Crossaint, egg, provolone cheese, chicken slice, house mayonnaise, Seasonal fruits', 'price' => '700 ETB', 'popular' => false],
            ['name' => 'Thick broish French toast', 'description' => 'Thick broish bread, egg, Cinnamon, custard cream, whipped cream, caramel sauce, apple, peanut, Granola, Strawberry, banana', 'price' => '650 ETB', 'popular' => false],
            ['name' => 'Green Yogurt parfit', 'description' => 'Avocado, yogurt, apple, Granola, Icing sugar, dark chocolate', 'price' => '750 ETB', 'popular' => false],
            ['name' => 'Strawberry yogurt parfit', 'description' => 'Strawberry, yogurt, Granola, white chocolate, Icing sugar', 'price' => '800 ETB', 'popular' => false],
            ['name' => 'Mango yogurt parfit', 'description' => 'Mango, yogurt, Granola, white chocolate, Icing sugar', 'price' => '700 ETB', 'popular' => false],
            ['name' => 'Caramel yogurt parfit', 'description' => 'Caramel sauce, yogurt, banana, Granola, white chocolate, Icing sugar', 'price' => '700 ETB', 'popular' => false],
            ['name' => 'Chocolate yogurt parfit', 'description' => 'Chocolate sauce, yogurt, banana, Granola, dark chocolate, Icing sugar', 'price' => '750 ETB', 'popular' => false],
            ['name' => 'Breakfast bruschetta', 'description' => 'Sourdough bread, egg, Ricotta cheese, tomato, pesto, balsamic vinger, olive oil', 'price' => '600 ETB', 'popular' => false],
            ['name' => 'Creamy shakshuka', 'description' => 'House made tomato sauce, fresh cream, egg, sourdough bread', 'price' => '650 ETB', 'popular' => false],
            ['name' => 'Teff chechebsa with egg', 'description' => 'Teff flour, house kibe, egg, honey, yogurt', 'price' => '600 ETB', 'popular' => false],
            ['name' => 'Fluffy pancake with caramel sauce', 'description' => 'Pancake batter, Strawberry, banana, custard cream, caramel sauce, Icing sugar', 'price' => '680 ETB', 'popular' => false],
            ['name' => 'Caramelized apple crumble waffle', 'description' => 'Waffle batter, Caramelized apple, Strawberry banana, custard cream, caramel sauce, Icing sugar', 'price' => '750 ETB', 'popular' => false],
        ];

        $this->createMenuItems($categoryMap['breakfast'], $breakfastItems, $extractPrice);

        // Lunch+Dinner items
        $lunchDinnerItems = [
            ['name' => 'Filet mignon steak with side of potato gratin and vegetables', 'description' => 'Freshly seasoned filet mignon steak meat, potato gratin, vegetables, pepper corn sauce', 'price' => '1600 ETB', 'popular' => true],
            ['name' => 'Garlic butter Grilled chicken with side of vegetables', 'description' => 'Freshly seasoned chicken breast, vegetables, mushroom sauce', 'price' => '1400 ETB', 'popular' => false],
            ['name' => 'Classic basil pesto hummus with side tortilla pita', 'description' => 'Freshly made Hummus, pesto, carrot, sweet corn, pickled Cucumber, Pickled onion, sundried tomato, Seseme seed, olive oil, Tortilla pita/ sourdough bread', 'price' => '1850 ETB', 'popular' => false],
            ['name' => 'Chicken ceaser salad', 'description' => 'Freshly seasond chicken breast, lettuce, ceaser dressing, Dijon Mustard, olive oil, parmesian cheese, croton, side sourdough bread', 'price' => '2500 ETB', 'popular' => false],
            ['name' => 'Tribean tuna salad', 'description' => 'Tuna can, pickled onion, pickled Cucumber, Pickled Green pepper, tomato, sweet corn, chick pea, kidney beans, honey mustard dressing, Seseme seed', 'price' => '1200 ETB', 'popular' => false],
            ['name' => 'MamoKacha special salad', 'description' => 'Baby leaf, lettuce, pickles, sundried tomato, sweet corn, steak meat, crouton, olive oil, Seseme seed, side sourdough bread', 'price' => '1500 ETB', 'popular' => false],
            ['name' => 'Grilled chicken wrap side gratin/Coleslaw', 'description' => 'Freshly seasoned chicken breast, avocado, provolone cheese, house mayonnaise, tortilla pita', 'price' => '1100 ETB', 'popular' => false],
            ['name' => 'Grilled chicken sandwich side gratin/Coleslaw', 'description' => 'Freshly seasoned chicken breast, provolone cheese, house mayonnaise, pikels, white/ brown bread', 'price' => '1100 ETB', 'popular' => false],
            ['name' => 'Rosted beef wrap Side gratin/coleslaw', 'description' => 'Freshly seasoned steak meat, avocado, provolone cheese, house mayonnaise, tortilla pita', 'price' => '1100 ETB', 'popular' => false],
            ['name' => 'Roasted beef sandwich side gratin/Coleslaw', 'description' => 'Freshly seasoned steak meat, provolone cheese, house mayonnaise, pikels, white/ brown bread', 'price' => '1150 ETB', 'popular' => false],
            ['name' => 'Crispy Chicken drum stick side gratin/ Coleslaw', 'description' => 'Freshly seasoned chicken leg, bread crump, egg, house mayonnaise, spicy mayonnaise', 'price' => '1500 ETB', 'popular' => false],
            ['name' => 'Grilled chicken drum stick side gratin/ Coleslaw', 'description' => 'Freshly seasoned chicken leg, house mayonnaise, spicy mayonnaise', 'price' => '1500 ETB', 'popular' => false],
            ['name' => 'Smoked Salmon sandwich with side coleslaw salad/ potato gratin', 'description' => 'Smoked salmon, philidalipya cream cheese, pickled onion, pickled cucumber, house mayonnaise', 'price' => '2200 ETB', 'popular' => false],
        ];

        $this->createMenuItems($categoryMap['lunch-dinner'], $lunchDinnerItems, $extractPrice);

        // Hot Drink items
        $hotDrinkItems = [
            ['name' => 'Tea', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '110 ETB', 'popular' => false],
            ['name' => 'Cinnamon tea', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '120 ETB', 'popular' => false],
            ['name' => 'Green Tea', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '150 ETB', 'popular' => false],
            ['name' => 'Ginger Tea', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '140 ETB', 'popular' => false],
            ['name' => 'Special Tea', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '300 ETB', 'popular' => false],
            ['name' => 'Special Tea With Alcohol', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '330 ETB', 'popular' => false],
            ['name' => 'Single espresso', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '170 ETB', 'popular' => false],
            ['name' => 'Double Espresso', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '220 ETB', 'popular' => false],
            ['name' => 'Turkish Coffee', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '350 ETB', 'popular' => false],
            ['name' => 'Americano', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '180 ETB', 'popular' => false],
            ['name' => 'Tea Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '300 ETB', 'popular' => false],
            ['name' => 'Pistacho Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '500 ETB', 'popular' => false],
            ['name' => 'Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '350 ETB', 'popular' => true],
            ['name' => 'Moca Late', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Cinnamon Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '400 ETB', 'popular' => false],
            ['name' => 'Spanish Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '400 ETB', 'popular' => false],
            ['name' => 'Matcha Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '500 ETB', 'popular' => false],
            ['name' => 'Caramel Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Cappuccino', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '400 ETB', 'popular' => true],
            ['name' => 'Hot chocolate', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '480 ETB', 'popular' => false],
            ['name' => 'Macchiato', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '200 ETB', 'popular' => false],
            ['name' => 'Double macchiato', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '300 ETB', 'popular' => false],
            ['name' => 'Steamed Milk', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '250 ETB', 'popular' => false],
            ['name' => 'Fasting Macchiato', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Fasting macchiato latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Double Caramel Macchiato', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '400 ETB', 'popular' => false],
            ['name' => 'Iced Americano', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '250 ETB', 'popular' => false],
            ['name' => 'Iced banana Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '400 ETB', 'popular' => false],
            ['name' => 'Iced moca Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '480 ETB', 'popular' => false],
            ['name' => 'Iced pistacho Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '500 ETB', 'popular' => false],
            ['name' => 'Iced pistacho vanilla Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Iced Caramel late', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '480 ETB', 'popular' => false],
            ['name' => 'Iced Matcha Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Iced Moca Strawberry Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Iced Pistacho Chocolate Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Iced fasting Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Affoogato', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '400 ETB', 'popular' => false],
            ['name' => 'Caramel Frappuccino', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Coffee Frappuccino', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '390 ETB', 'popular' => false],
            ['name' => 'Moca Frappuccino', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Pistachio Frappuccino', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Matcha Frappuccino', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'V60', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '280 ETB', 'popular' => false],
            ['name' => 'French Press', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '280 ETB', 'popular' => false],
            ['name' => 'Syphon Coffee', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '350 ETB', 'popular' => false],
            ['name' => 'Chemex', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '280 ETB', 'popular' => false],
            ['name' => 'Mint Lemonade', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '350 ETB', 'popular' => false],
            ['name' => 'Strawberry Lemonade', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '350 ETB', 'popular' => false],
            ['name' => 'Ginger Shot', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '120 ETB', 'popular' => false],
            ['name' => 'Ginger Shot With Honey', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '150 ETB', 'popular' => false],
            ['name' => 'Ginger Lemonade', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '350 ETB', 'popular' => false],
            ['name' => 'Ube Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '500 ETB', 'popular' => false],
            ['name' => 'Ube Iced Latte', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Coffee mate Macchiato', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '350 ETB', 'popular' => false],
            ['name' => 'Iced coffee mate macchiato', 'description' => 'Price including VAT 15% and SC 5%', 'price' => '400 ETB', 'popular' => false],
        ];

        $this->createMenuItems($categoryMap['hot-drink'], $hotDrinkItems, $extractPrice);

        // Spirits items
        $spiritsItems = [
            ['name' => 'Vodka', 'description' => 'Red wine, grape fruit', 'price' => '1300 ETB', 'popular' => false],
            ['name' => 'Gin', 'description' => 'Lemon, thyme', 'price' => '1300 ETB', 'popular' => false],
            ['name' => 'Rum', 'description' => 'Passion fruit, mint', 'price' => '1800 ETB', 'popular' => false],
            ['name' => 'Tequila', 'description' => 'Orange, cranberry syrup', 'price' => '1800 ETB', 'popular' => false],
            ['name' => 'Campari', 'description' => 'Rosso, sparking water', 'price' => '1600 ETB', 'popular' => false],
            ['name' => 'Aperol', 'description' => 'Prosecco', 'price' => '1500 ETB', 'popular' => false],
            ['name' => 'Malibu', 'description' => 'Blue curacao, pineapple', 'price' => '1600 ETB', 'popular' => false],
            ['name' => 'Whisky', 'description' => 'Mint, sparking water', 'price' => '1800 ETB', 'popular' => false],
            ['name' => 'Cognac', 'description' => 'Chocolate', 'price' => '2500 ETB', 'popular' => false],
            ['name' => 'White wine', 'description' => 'Prosecco', 'price' => '1100 ETB', 'popular' => false],
            ['name' => 'Limoncello', 'description' => 'Aperol, prosecco', 'price' => '1800 ETB', 'popular' => false],
            ['name' => 'Jagamaster', 'description' => 'Kahlua coffee, espresso', 'price' => '1700 ETB', 'popular' => false],
        ];

        $this->createMenuItems($categoryMap['spirits'], $spiritsItems, $extractPrice);

        // Cocktail Shot items
        $cocktailShotItems = [
            ['name' => 'Shot One', 'description' => 'Premium cocktail shot', 'price' => '1000 ETB', 'popular' => false],
            ['name' => 'Shot Two', 'description' => 'Premium cocktail shot', 'price' => '500 ETB', 'popular' => false],
            ['name' => 'Dark Shot', 'description' => 'Premium dark cocktail shot', 'price' => '700 ETB', 'popular' => false],
        ];

        $this->createMenuItems($categoryMap['cocktail-shot'], $cocktailShotItems, $extractPrice);

        // Wine items
        $wineItems = [
            ['name' => 'CH* MALESCASSE (Red wine)', 'description' => 'Premium red wine', 'price' => '24000 ETB', 'popular' => true],
            ['name' => 'CH* DE GARBES (Red wine)', 'description' => 'Premium red wine', 'price' => '6000 ETB', 'popular' => false],
            ['name' => 'FANTINEL CABERNET (Red wine)', 'description' => 'Premium red wine', 'price' => '9500 ETB', 'popular' => false],
            ['name' => 'FANTINEL PINO GRIGIO (White wine)', 'description' => 'Premium white wine', 'price' => '9500 ETB', 'popular' => false],
            ['name' => 'LAMOTH PARROT (White wine)', 'description' => 'Premium white wine', 'price' => '4500 ETB', 'popular' => false],
            ['name' => 'ZONIN DOC (ICE) (Sparkling wine)', 'description' => 'Premium sparkling wine', 'price' => '8000 ETB', 'popular' => false],
            ['name' => 'ZONIN EXTRA DRY (Sparkling wine)', 'description' => 'Premium sparkling wine', 'price' => '8000 ETB', 'popular' => false],
            ['name' => 'ZONIN PROSECCO DOC (Sparkling wine)', 'description' => 'Premium sparkling wine', 'price' => '7500 ETB', 'popular' => false],
            ['name' => 'GRANDIAL ROSE (Sparkling wine)', 'description' => 'Premium sparkling wine', 'price' => '6000 ETB', 'popular' => false],
        ];

        $this->createMenuItems($categoryMap['wine'], $wineItems, $extractPrice);

        // Juice items
        $juiceItems = [
            ['name' => 'Orange', 'description' => 'Fresh orange juice', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Pineapple ginger lemon', 'description' => 'Refreshing pineapple, ginger, and lemon blend', 'price' => '400 ETB', 'popular' => false],
            ['name' => 'Strawberry banana smoothie', 'description' => 'Creamy strawberry and banana smoothie', 'price' => '500 ETB', 'popular' => false],
            ['name' => 'Mango spinach smoothie', 'description' => 'Healthy mango and spinach smoothie', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Cacao peanut banana', 'description' => 'Rich cacao, peanut, and banana blend', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Avocado boast', 'description' => 'Creamy avocado smoothie', 'price' => '450 ETB', 'popular' => false],
        ];

        $this->createMenuItems($categoryMap['juice'], $juiceItems, $extractPrice);

        // Pastry items
        $pastryItems = [
            ['name' => 'Chocolate tart', 'description' => 'Rich chocolate tart', 'price' => '350 ETB', 'popular' => false],
            ['name' => 'Red velveit cake', 'description' => 'Classic red velvet cake', 'price' => '550 ETB', 'popular' => true],
            ['name' => 'Chocolate mousse', 'description' => 'Creamy chocolate mousse', 'price' => '500 ETB', 'popular' => false],
            ['name' => 'Chocolate', 'description' => 'Premium chocolate dessert', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Caramel', 'description' => 'Sweet caramel dessert', 'price' => '350 ETB', 'popular' => false],
            ['name' => 'Boxegna', 'description' => 'Traditional boxegna pastry', 'price' => '280 ETB', 'popular' => false],
            ['name' => 'Black and white', 'description' => 'Elegant black and white dessert', 'price' => '380 ETB', 'popular' => false],
            ['name' => 'Vanilla mousse', 'description' => 'Creamy vanilla mousse', 'price' => '400 ETB', 'popular' => false],
            ['name' => 'Strawberry mousse', 'description' => 'Fresh strawberry mousse', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Checocaramel mousse', 'description' => 'Chocolate caramel mousse', 'price' => '450 ETB', 'popular' => false],
            ['name' => 'Opera', 'description' => 'Classic opera cake', 'price' => '550 ETB', 'popular' => false],
            ['name' => 'Opera caramel', 'description' => 'Opera cake with caramel', 'price' => '600 ETB', 'popular' => false],
            ['name' => 'Philadelphia Cheese cake', 'description' => 'Creamy Philadelphia cheesecake', 'price' => '700 ETB', 'popular' => true],
            ['name' => 'Brownie Philadelphia Cheese cake', 'description' => 'Brownie with Philadelphia cheesecake', 'price' => '650 ETB', 'popular' => false],
            ['name' => 'Chocolate cheese cake', 'description' => 'Rich chocolate cheesecake', 'price' => '750 ETB', 'popular' => false],
            ['name' => 'Cinnamon without cream', 'description' => 'Cinnamon pastry without cream', 'price' => '270 ETB', 'popular' => false],
            ['name' => 'Cinnamon with cream', 'description' => 'Cinnamon pastry with cream', 'price' => '300 ETB', 'popular' => false],
            ['name' => 'Plain Croissant', 'description' => 'Classic butter croissant', 'price' => '300 ETB', 'popular' => false],
            ['name' => 'Chocolate croissant', 'description' => 'Buttery croissant filled with chocolate', 'price' => '350 ETB', 'popular' => true],
            ['name' => 'Mille-feuille', 'description' => 'Classic French mille-feuille pastry', 'price' => '300 ETB', 'popular' => false],
            ['name' => 'English cake', 'description' => 'Traditional English cake', 'price' => '250 ETB', 'popular' => false],
        ];

        $this->createMenuItems($categoryMap['pastry'], $pastryItems, $extractPrice);
    }

    private function createMenuItems($categoryId, $items, $extractPrice)
    {
        $order = 1;
        foreach ($items as $item) {
            MenuItem::updateOrCreate(
                [
                    'category_id' => $categoryId,
                    'slug' => Str::slug($item['name']),
                ],
                [
                    'category_id' => $categoryId,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'description' => $item['description'],
                    'price' => $extractPrice($item['price']),
                    'is_special' => isset($item['popular']) && $item['popular'] === true,
                    'is_active' => true,
                    'order' => $order++,
                ]
            );
        }
    }
}


