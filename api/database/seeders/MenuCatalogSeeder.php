<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Café menu categories and items aligned with the public site menu (ETB prices).
 * Idempotent: uses updateOrCreate on category slug and item slug.
 *
 * Run after migrations: php artisan db:seed --class=MenuCatalogSeeder
 * Or via DatabaseSeeder.
 */
class MenuCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            [
                'name' => 'Hot Coffee',
                'slug' => 'hot-coffee',
                'description' => 'Espresso-based drinks and hand-brewed coffee.',
                'order' => 1,
                'items' => [
                    ['name' => 'Espresso', 'description' => 'Pure Ethiopian single shot, bold and aromatic', 'price' => '85 ETB', 'special' => false],
                    ['name' => 'Macchiato', 'description' => 'Traditional Ethiopian-style, layered to perfection', 'price' => '90 ETB', 'special' => false],
                    ['name' => 'Cappuccino', 'description' => 'Velvety steamed milk with rich espresso', 'price' => '120 ETB', 'special' => false],
                    ['name' => 'Café Latte', 'description' => 'Smooth espresso with silky steamed milk and latte art', 'price' => '130 ETB', 'special' => true],
                    ['name' => 'Pour Over', 'description' => 'Hand-brewed single origin, highlighting delicate flavors', 'price' => '150 ETB', 'special' => true],
                    ['name' => 'Americano', 'description' => 'Espresso lengthened with hot water, clean finish', 'price' => '95 ETB', 'special' => false],
                    ['name' => 'Flat White', 'description' => 'Double ristretto with microfoam for a silky mouthfeel', 'price' => '125 ETB', 'special' => false],
                    ['name' => 'Cappuccino Mocha', 'description' => 'Espresso, cocoa, steamed milk, and light foam', 'price' => '135 ETB', 'special' => false],
                    ['name' => 'Cortado', 'description' => 'Equal parts espresso and warm milk—balanced and bold', 'price' => '115 ETB', 'special' => false],
                    ['name' => 'Cappuccino Viennese', 'description' => 'Cappuccino topped with whipped cream and cocoa dust', 'price' => '140 ETB', 'special' => false],
                ],
            ],
            [
                'name' => 'Cold Drinks',
                'slug' => 'cold-drinks',
                'description' => 'Iced coffee, cold brew, and refreshers.',
                'order' => 2,
                'items' => [
                    ['name' => 'Iced Latte', 'description' => 'Chilled espresso with cold milk over ice', 'price' => '140 ETB', 'special' => true],
                    ['name' => 'Cold Brew', 'description' => 'Slow-steeped for 18 hours, smooth and rich', 'price' => '150 ETB', 'special' => false],
                    ['name' => 'Iced Macchiato', 'description' => 'Ethiopian macchiato served over ice', 'price' => '110 ETB', 'special' => false],
                    ['name' => 'Coffee Frappe', 'description' => 'Blended iced coffee with cream', 'price' => '160 ETB', 'special' => false],
                    ['name' => 'Iced Americano', 'description' => 'Espresso over ice, topped with cold water', 'price' => '105 ETB', 'special' => false],
                    ['name' => 'Iced Mocha', 'description' => 'Espresso, chocolate, cold milk, and ice', 'price' => '155 ETB', 'special' => false],
                    ['name' => 'Sparkling Lemonade', 'description' => 'House-made citrus with sparkling water', 'price' => '95 ETB', 'special' => false],
                    ['name' => 'Iced Caramel Latte', 'description' => 'Espresso, caramel, and cold milk over ice', 'price' => '165 ETB', 'special' => false],
                ],
            ],
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Breakfast, sandwiches, salads, and light plates.',
                'order' => 3,
                'items' => [
                    ['name' => 'Avocado Toast', 'description' => 'Sourdough, smashed avocado, poached eggs, microgreens', 'price' => '220 ETB', 'special' => true],
                    ['name' => 'Croissant', 'description' => 'Freshly baked butter croissant, flaky and golden', 'price' => '120 ETB', 'special' => false],
                    ['name' => 'Pastry Selection', 'description' => 'Daily rotating selection of artisan pastries', 'price' => '100 ETB', 'special' => false],
                    ['name' => 'Breakfast Bowl', 'description' => 'Granola, yogurt, fresh fruits, and honey drizzle', 'price' => '180 ETB', 'special' => false],
                    ['name' => 'Chicken & Avocado Sandwich', 'description' => 'Grilled chicken, avocado, tomato, and herb aioli on ciabatta', 'price' => '240 ETB', 'special' => false],
                    ['name' => 'Caprese Salad', 'description' => 'Fresh mozzarella, tomato, basil, and olive oil', 'price' => '200 ETB', 'special' => false],
                    ['name' => 'Smoked Salmon Bagel', 'description' => 'Cream cheese, capers, red onion, and dill', 'price' => '260 ETB', 'special' => true],
                    ['name' => 'Vegetable Quiche', 'description' => 'Seasonal vegetables in a buttery pastry shell', 'price' => '190 ETB', 'special' => false],
                    ['name' => 'Soup of the Day', 'description' => 'Ask your server for today’s seasonal soup', 'price' => '150 ETB', 'special' => false],
                ],
            ],
            [
                'name' => 'Fresh Juice',
                'slug' => 'fresh-juice',
                'description' => 'Cold-pressed and freshly squeezed juices.',
                'order' => 4,
                'items' => [
                    ['name' => 'Fresh Orange Juice', 'description' => 'Freshly squeezed, no additives', 'price' => '100 ETB', 'special' => false],
                    ['name' => 'Green Detox', 'description' => 'Spinach, apple, ginger, and lemon blend', 'price' => '120 ETB', 'special' => false],
                    ['name' => 'Tropical Mix', 'description' => 'Mango, pineapple, and passion fruit', 'price' => '110 ETB', 'special' => false],
                    ['name' => 'Berry Boost', 'description' => 'Strawberry, blueberry, and apple', 'price' => '125 ETB', 'special' => false],
                    ['name' => 'Carrot & Ginger', 'description' => 'Cooling carrot with a ginger kick', 'price' => '115 ETB', 'special' => false],
                    ['name' => 'Watermelon Cooler', 'description' => 'Seasonal watermelon, mint, and lime', 'price' => '105 ETB', 'special' => false],
                    ['name' => 'Beet & Apple', 'description' => 'Earthy beet balanced with sweet apple', 'price' => '125 ETB', 'special' => false],
                ],
            ],
            [
                'name' => 'Tea & Chocolate',
                'slug' => 'tea-chocolate',
                'description' => 'Teas, hot chocolate, and specialty lattes.',
                'order' => 5,
                'items' => [
                    ['name' => 'Ethiopian Spiced Tea', 'description' => 'Black tea with cinnamon, cardamom, and cloves', 'price' => '75 ETB', 'special' => false],
                    ['name' => 'Chai Latte', 'description' => 'Spiced black tea concentrate with steamed milk', 'price' => '110 ETB', 'special' => false],
                    ['name' => 'Green Tea', 'description' => 'Premium loose-leaf green tea, served hot', 'price' => '70 ETB', 'special' => false],
                    ['name' => 'Peppermint Tea', 'description' => 'Caffeine-free herbal infusion', 'price' => '65 ETB', 'special' => false],
                    ['name' => 'Hot Chocolate', 'description' => 'Rich cocoa with steamed milk and whipped cream', 'price' => '115 ETB', 'special' => false],
                    ['name' => 'White Hot Chocolate', 'description' => 'Creamy white chocolate and steamed milk', 'price' => '125 ETB', 'special' => false],
                    ['name' => 'Matcha Latte', 'description' => 'Ceremonial-grade matcha with steamed milk', 'price' => '140 ETB', 'special' => true],
                    ['name' => 'Turmeric Latte', 'description' => 'Golden milk with turmeric, ginger, and honey', 'price' => '120 ETB', 'special' => false],
                ],
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'description' => 'Pastries, cakes, and sweet finishes.',
                'order' => 6,
                'items' => [
                    ['name' => 'Tiramisu', 'description' => 'Classic espresso-soaked ladyfingers and mascarpone', 'price' => '180 ETB', 'special' => true],
                    ['name' => 'Chocolate Brownie', 'description' => 'Warm brownie with vanilla ice cream', 'price' => '150 ETB', 'special' => false],
                    ['name' => 'New York Cheesecake', 'description' => 'Creamy baked cheesecake with berry compote', 'price' => '190 ETB', 'special' => false],
                    ['name' => 'Affogato', 'description' => 'Vanilla gelato drowned in a shot of hot espresso', 'price' => '135 ETB', 'special' => false],
                    ['name' => 'Honey Cake', 'description' => 'Layered Ethiopian-style honey cake', 'price' => '160 ETB', 'special' => false],
                    ['name' => 'Fruit Tart', 'description' => 'Buttery shell with pastry cream and fresh fruit', 'price' => '170 ETB', 'special' => false],
                    ['name' => 'Ice Cream Sundae', 'description' => 'Three scoops, chocolate sauce, and nuts', 'price' => '145 ETB', 'special' => false],
                    ['name' => 'Baklava Plate', 'description' => 'Phyllo, nuts, and honey syrup—two pieces', 'price' => '130 ETB', 'special' => false],
                ],
            ],
        ];

        foreach ($catalog as $entry) {
            $items = $entry['items'];

            $category = MenuCategory::updateOrCreate(
                ['slug' => $entry['slug']],
                [
                    'name' => $entry['name'],
                    'description' => $entry['description'],
                    'is_active' => true,
                    'order' => $entry['order'],
                ]
            );

            $order = 1;
            foreach ($items as $item) {
                $itemSlug = $entry['slug'].'-'.Str::slug($item['name']);
                MenuItem::updateOrCreate(
                    ['slug' => $itemSlug],
                    [
                        'category_id' => $category->id,
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'price' => $this->parseEtbPrice($item['price']),
                        'is_special' => $item['special'],
                        'is_active' => true,
                        'image_id' => null,
                        'order' => $order++,
                    ]
                );
            }
        }

        if ($this->command) {
            $this->command->info('Menu catalog seeded: '.count($catalog).' categories.');
        }
    }

    private function parseEtbPrice(string $price): float
    {
        if (preg_match('/([\d,]+(?:\.\d+)?)/', $price, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }

        return 0.0;
    }
}
