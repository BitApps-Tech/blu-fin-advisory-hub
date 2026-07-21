-- Manual insert SQL equivalent to `MenuCatalogSeeder` (MySQL / MariaDB).
-- Requires tables: `menu_categories`, `menu_items` (see migrations).
-- Uses slug-based upsert so you can re-run safely.
--
-- Categories block uses `INSERT ... AS new ON DUPLICATE KEY UPDATE` (MySQL 8.0.19+).
-- If that fails on an older server, run the six `INSERT IGNORE` lines in the
-- "LEGACY_CATEGORIES" comment block at the bottom of this file instead, then run the menu_items section.
--
-- For older MySQL without INSERT ... AS alias: run the DELETE section first on a dev DB only,
-- or remove conflicting rows by slug before INSERT.

SET NAMES utf8mb4;
SET @ts = NOW();

-- ---------------------------------------------------------------------------
-- Categories (unique on `slug`)
-- ---------------------------------------------------------------------------
INSERT INTO `menu_categories` (`name`, `slug`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
('Hot Coffee', 'hot-coffee', 'Espresso-based drinks and hand-brewed coffee.', 1, 1, @ts, @ts),
('Cold Drinks', 'cold-drinks', 'Iced coffee, cold brew, and refreshers.', 1, 2, @ts, @ts),
('Food', 'food', 'Breakfast, sandwiches, salads, and light plates.', 1, 3, @ts, @ts),
('Fresh Juice', 'fresh-juice', 'Cold-pressed and freshly squeezed juices.', 1, 4, @ts, @ts),
('Tea & Chocolate', 'tea-chocolate', 'Teas, hot chocolate, and specialty lattes.', 1, 5, @ts, @ts),
('Desserts', 'desserts', 'Pastries, cakes, and sweet finishes.', 1, 6, @ts, @ts)
AS new
ON DUPLICATE KEY UPDATE
  `name` = new.`name`,
  `description` = new.`description`,
  `is_active` = new.`is_active`,
  `order` = new.`order`,
  `updated_at` = new.`updated_at`;

-- ---------------------------------------------------------------------------
-- Menu items: `category_id` resolved by category slug; `slug` = `{cat}-{item}` (globally unique)
-- `is_special` = 1 where seeder had special=true
-- ---------------------------------------------------------------------------

-- Hot Coffee
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Espresso', 'hot-coffee-espresso', 'Pure Ethiopian single shot, bold and aromatic', 85.00, 0, 1, NULL, 1, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Macchiato', 'hot-coffee-macchiato', 'Traditional Ethiopian-style, layered to perfection', 90.00, 0, 1, NULL, 2, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Cappuccino', 'hot-coffee-cappuccino', 'Velvety steamed milk with rich espresso', 120.00, 0, 1, NULL, 3, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Café Latte', 'hot-coffee-cafe-latte', 'Smooth espresso with silky steamed milk and latte art', 130.00, 1, 1, NULL, 4, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Pour Over', 'hot-coffee-pour-over', 'Hand-brewed single origin, highlighting delicate flavors', 150.00, 1, 1, NULL, 5, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Americano', 'hot-coffee-americano', 'Espresso lengthened with hot water, clean finish', 95.00, 0, 1, NULL, 6, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Flat White', 'hot-coffee-flat-white', 'Double ristretto with microfoam for a silky mouthfeel', 125.00, 0, 1, NULL, 7, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Cappuccino Mocha', 'hot-coffee-cappuccino-mocha', 'Espresso, cocoa, steamed milk, and light foam', 135.00, 0, 1, NULL, 8, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Cortado', 'hot-coffee-cortado', 'Equal parts espresso and warm milk—balanced and bold', 115.00, 0, 1, NULL, 9, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Cappuccino Viennese', 'hot-coffee-cappuccino-viennese', 'Cappuccino topped with whipped cream and cocoa dust', 140.00, 0, 1, NULL, 10, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'hot-coffee'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;

-- Cold Drinks
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Iced Latte', 'cold-drinks-iced-latte', 'Chilled espresso with cold milk over ice', 140.00, 1, 1, NULL, 1, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'cold-drinks'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Cold Brew', 'cold-drinks-cold-brew', 'Slow-steeped for 18 hours, smooth and rich', 150.00, 0, 1, NULL, 2, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'cold-drinks'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Iced Macchiato', 'cold-drinks-iced-macchiato', 'Ethiopian macchiato served over ice', 110.00, 0, 1, NULL, 3, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'cold-drinks'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Coffee Frappe', 'cold-drinks-coffee-frappe', 'Blended iced coffee with cream', 160.00, 0, 1, NULL, 4, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'cold-drinks'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Iced Americano', 'cold-drinks-iced-americano', 'Espresso over ice, topped with cold water', 105.00, 0, 1, NULL, 5, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'cold-drinks'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Iced Mocha', 'cold-drinks-iced-mocha', 'Espresso, chocolate, cold milk, and ice', 155.00, 0, 1, NULL, 6, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'cold-drinks'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Sparkling Lemonade', 'cold-drinks-sparkling-lemonade', 'House-made citrus with sparkling water', 95.00, 0, 1, NULL, 7, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'cold-drinks'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Iced Caramel Latte', 'cold-drinks-iced-caramel-latte', 'Espresso, caramel, and cold milk over ice', 165.00, 0, 1, NULL, 8, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'cold-drinks'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;

-- Food
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Avocado Toast', 'food-avocado-toast', 'Sourdough, smashed avocado, poached eggs, microgreens', 220.00, 1, 1, NULL, 1, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Croissant', 'food-croissant', 'Freshly baked butter croissant, flaky and golden', 120.00, 0, 1, NULL, 2, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Pastry Selection', 'food-pastry-selection', 'Daily rotating selection of artisan pastries', 100.00, 0, 1, NULL, 3, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Breakfast Bowl', 'food-breakfast-bowl', 'Granola, yogurt, fresh fruits, and honey drizzle', 180.00, 0, 1, NULL, 4, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Chicken & Avocado Sandwich', 'food-chicken-avocado-sandwich', 'Grilled chicken, avocado, tomato, and herb aioli on ciabatta', 240.00, 0, 1, NULL, 5, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Caprese Salad', 'food-caprese-salad', 'Fresh mozzarella, tomato, basil, and olive oil', 200.00, 0, 1, NULL, 6, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Smoked Salmon Bagel', 'food-smoked-salmon-bagel', 'Cream cheese, capers, red onion, and dill', 260.00, 1, 1, NULL, 7, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Vegetable Quiche', 'food-vegetable-quiche', 'Seasonal vegetables in a buttery pastry shell', 190.00, 0, 1, NULL, 8, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Soup of the Day', 'food-soup-of-the-day', 'Ask your server for today''s seasonal soup', 150.00, 0, 1, NULL, 9, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'food'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;

-- Fresh Juice
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Fresh Orange Juice', 'fresh-juice-fresh-orange-juice', 'Freshly squeezed, no additives', 100.00, 0, 1, NULL, 1, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'fresh-juice'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Green Detox', 'fresh-juice-green-detox', 'Spinach, apple, ginger, and lemon blend', 120.00, 0, 1, NULL, 2, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'fresh-juice'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Tropical Mix', 'fresh-juice-tropical-mix', 'Mango, pineapple, and passion fruit', 110.00, 0, 1, NULL, 3, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'fresh-juice'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Berry Boost', 'fresh-juice-berry-boost', 'Strawberry, blueberry, and apple', 125.00, 0, 1, NULL, 4, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'fresh-juice'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Carrot & Ginger', 'fresh-juice-carrot-ginger', 'Cooling carrot with a ginger kick', 115.00, 0, 1, NULL, 5, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'fresh-juice'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Watermelon Cooler', 'fresh-juice-watermelon-cooler', 'Seasonal watermelon, mint, and lime', 105.00, 0, 1, NULL, 6, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'fresh-juice'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Beet & Apple', 'fresh-juice-beet-apple', 'Earthy beet balanced with sweet apple', 125.00, 0, 1, NULL, 7, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'fresh-juice'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;

-- Tea & Chocolate
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Ethiopian Spiced Tea', 'tea-chocolate-ethiopian-spiced-tea', 'Black tea with cinnamon, cardamom, and cloves', 75.00, 0, 1, NULL, 1, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'tea-chocolate'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Chai Latte', 'tea-chocolate-chai-latte', 'Spiced black tea concentrate with steamed milk', 110.00, 0, 1, NULL, 2, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'tea-chocolate'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Green Tea', 'tea-chocolate-green-tea', 'Premium loose-leaf green tea, served hot', 70.00, 0, 1, NULL, 3, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'tea-chocolate'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Peppermint Tea', 'tea-chocolate-peppermint-tea', 'Caffeine-free herbal infusion', 65.00, 0, 1, NULL, 4, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'tea-chocolate'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Hot Chocolate', 'tea-chocolate-hot-chocolate', 'Rich cocoa with steamed milk and whipped cream', 115.00, 0, 1, NULL, 5, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'tea-chocolate'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'White Hot Chocolate', 'tea-chocolate-white-hot-chocolate', 'Creamy white chocolate and steamed milk', 125.00, 0, 1, NULL, 6, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'tea-chocolate'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Matcha Latte', 'tea-chocolate-matcha-latte', 'Ceremonial-grade matcha with steamed milk', 140.00, 1, 1, NULL, 7, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'tea-chocolate'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Turmeric Latte', 'tea-chocolate-turmeric-latte', 'Golden milk with turmeric, ginger, and honey', 120.00, 0, 1, NULL, 8, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'tea-chocolate'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;

-- Desserts
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Tiramisu', 'desserts-tiramisu', 'Classic espresso-soaked ladyfingers and mascarpone', 180.00, 1, 1, NULL, 1, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'desserts'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Chocolate Brownie', 'desserts-chocolate-brownie', 'Warm brownie with vanilla ice cream', 150.00, 0, 1, NULL, 2, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'desserts'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'New York Cheesecake', 'desserts-new-york-cheesecake', 'Creamy baked cheesecake with berry compote', 190.00, 0, 1, NULL, 3, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'desserts'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Affogato', 'desserts-affogato', 'Vanilla gelato drowned in a shot of hot espresso', 135.00, 0, 1, NULL, 4, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'desserts'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Honey Cake', 'desserts-honey-cake', 'Layered Ethiopian-style honey cake', 160.00, 0, 1, NULL, 5, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'desserts'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Fruit Tart', 'desserts-fruit-tart', 'Buttery shell with pastry cream and fresh fruit', 170.00, 0, 1, NULL, 6, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'desserts'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Ice Cream Sundae', 'desserts-ice-cream-sundae', 'Three scoops, chocolate sauce, and nuts', 145.00, 0, 1, NULL, 7, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'desserts'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;
INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `is_special`, `is_active`, `image_id`, `order`, `created_at`, `updated_at`, `deleted_at`)
SELECT c.id, 'Baklava Plate', 'desserts-baklava-plate', 'Phyllo, nuts, and honey syrup—two pieces', 130.00, 0, 1, NULL, 8, @ts, @ts, NULL FROM menu_categories c WHERE c.slug = 'desserts'
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`), `name`=VALUES(`name`), `description`=VALUES(`description`), `price`=VALUES(`price`), `is_special`=VALUES(`is_special`), `is_active`=VALUES(`is_active`), `image_id`=VALUES(`image_id`), `order`=VALUES(`order`), `updated_at`=VALUES(`updated_at`), `deleted_at`=NULL;

-- ---------------------------------------------------------------------------
-- LEGACY_CATEGORIES (MySQL 5.7 / MariaDB without row alias on INSERT … VALUES)
-- Uncomment and run only if the multi-row INSERT at the top fails. Skip if rows exist.
-- ---------------------------------------------------------------------------
/*
INSERT IGNORE INTO `menu_categories` (`name`, `slug`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
('Hot Coffee', 'hot-coffee', 'Espresso-based drinks and hand-brewed coffee.', 1, 1, NOW(), NOW());
INSERT IGNORE INTO `menu_categories` (`name`, `slug`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
('Cold Drinks', 'cold-drinks', 'Iced coffee, cold brew, and refreshers.', 1, 2, NOW(), NOW());
INSERT IGNORE INTO `menu_categories` (`name`, `slug`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
('Food', 'food', 'Breakfast, sandwiches, salads, and light plates.', 1, 3, NOW(), NOW());
INSERT IGNORE INTO `menu_categories` (`name`, `slug`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
('Fresh Juice', 'fresh-juice', 'Cold-pressed and freshly squeezed juices.', 1, 4, NOW(), NOW());
INSERT IGNORE INTO `menu_categories` (`name`, `slug`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
('Tea & Chocolate', 'tea-chocolate', 'Teas, hot chocolate, and specialty lattes.', 1, 5, NOW(), NOW());
INSERT IGNORE INTO `menu_categories` (`name`, `slug`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
('Desserts', 'desserts', 'Pastries, cakes, and sweet finishes.', 1, 6, NOW(), NOW());
*/
