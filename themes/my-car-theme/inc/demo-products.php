<?php
/**
 * Demo Products Data
 * 
 * @package MyCarTheme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get demo products data
 */
function my_car_get_demo_products() {
    $template_dir = get_template_directory_uri();
    
    return array(
        'categories' => array(
            array(
                'name' => 'السيارات الصغيرة',
                'slug' => 'small-cars',
                'description' => 'تبحث عن سيارة يومية للتنقلات اليومية والذهاب إلى العمل؟ هذه الفئة توفر لك سيارات بأسعار مناسبة لميزانيتك.',
                'image' => $template_dir . '/my-car/assets/product.png',
            ),
            array(
                'name' => 'سيدان & كومباكت',
                'slug' => 'sedan-compact',
                'description' => 'توفر لك في هذه الفئة مجموعة متنوعة سيارات بمساحات تناسب مع احتياجاتك المختلفة إن كانت للتجوال داخل المدينة أو للسفر ما بين المدن.',
                'image' => $template_dir . '/my-car/assets/mg.png',
            ),
            array(
                'name' => 'السيارات العائلية',
                'slug' => 'family-cars',
                'description' => 'تبحث عن مساحة واسعة و سعة تخزينية كبيرة للرحلات؟ هذه الفئة هي خيارك المفضل، سيارات بسعة تصل لـ سبع ركاب، مهما كانت عائلتك كبيرة.',
                'image' => $template_dir . '/my-car/assets/small-car.png',
            ),
            array(
                'name' => 'دفع رباعي & كروس اوفر',
                'slug' => 'suv-crossover',
                'description' => 'سيارات قوية ومتعددة الاستخدامات مناسبة للطرق الوعرة والرحلات الطويلة.',
                'image' => $template_dir . '/my-car/assets/product.png',
            ),
            array(
                'name' => 'فخمة',
                'slug' => 'luxury',
                'description' => 'سيارات فاخرة بتصميم أنيق ومميزات متقدمة للراحة والرفاهية.',
                'image' => $template_dir . '/my-car/assets/product.png',
            ),
        ),
        'products' => array(
            // Small Cars
            array(
                'name' => 'هيونداي اكسنت 2025',
                'slug' => 'hyundai-accent-2025',
                'description' => 'سيارة صغيرة اقتصادية ومناسبة للاستخدام اليومي. تتميز بكفاءة استهلاك الوقود والراحة في القيادة.',
                'short_description' => 'سيارة صغيرة اقتصادية ومناسبة للاستخدام اليومي',
                'price' => 200,
                'regular_price' => 200,
                'category' => 'small-cars',
                'image' => $template_dir . '/my-car/assets/product.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '4',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            array(
                'name' => 'تويوتا ياريس 2025',
                'slug' => 'toyota-yaris-2025',
                'description' => 'سيارة صغيرة موثوقة وعملية، مثالية للمدينة والرحلات القصيرة.',
                'short_description' => 'سيارة صغيرة موثوقة وعملية',
                'price' => 180,
                'regular_price' => 180,
                'category' => 'small-cars',
                'image' => $template_dir . '/my-car/assets/product.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '4',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            array(
                'name' => 'نيسان سنترا 2025',
                'slug' => 'nissan-sentra-2025',
                'description' => 'سيارة صغيرة أنيقة بتصميم عصري وتقنيات متقدمة.',
                'short_description' => 'سيارة صغيرة أنيقة بتصميم عصري',
                'price' => 220,
                'regular_price' => 220,
                'category' => 'small-cars',
                'image' => $template_dir . '/my-car/assets/product.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '4',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            
            // Sedan & Compact
            array(
                'name' => 'هوندا سيفيك 2025',
                'slug' => 'honda-civic-2025',
                'description' => 'سيارة سيدان متوسطة الحجم بتصميم رياضي وأداء قوي.',
                'short_description' => 'سيارة سيدان متوسطة الحجم بتصميم رياضي',
                'price' => 350,
                'regular_price' => 350,
                'category' => 'sedan-compact',
                'image' => $template_dir . '/my-car/assets/mg.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '4',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            array(
                'name' => 'تويوتا كامري 2025',
                'slug' => 'toyota-camry-2025',
                'description' => 'سيارة سيدان فاخرة بمساحة واسعة وراحة عالية.',
                'short_description' => 'سيارة سيدان فاخرة بمساحة واسعة',
                'price' => 450,
                'regular_price' => 450,
                'category' => 'sedan-compact',
                'image' => $template_dir . '/my-car/assets/mg.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '4',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            array(
                'name' => 'نيسان التيما 2025',
                'slug' => 'nissan-altima-2025',
                'description' => 'سيارة سيدان عائلية بمساحة واسعة وتقنيات متقدمة.',
                'short_description' => 'سيارة سيدان عائلية بمساحة واسعة',
                'price' => 400,
                'regular_price' => 400,
                'category' => 'sedan-compact',
                'image' => $template_dir . '/my-car/assets/mg.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '4',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            
            // Family Cars
            array(
                'name' => 'تويوتا هايلاندر 2025',
                'slug' => 'toyota-highlander-2025',
                'description' => 'سيارة عائلية كبيرة بسعة 7 ركاب، مثالية للرحلات العائلية الطويلة.',
                'short_description' => 'سيارة عائلية كبيرة بسعة 7 ركاب',
                'price' => 600,
                'regular_price' => 600,
                'category' => 'family-cars',
                'image' => $template_dir . '/my-car/assets/small-car.png',
                'attributes' => array(
                    'عدد الركاب' => '7',
                    'عدد الأبواب' => '5',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            array(
                'name' => 'هوندا بيلوت 2025',
                'slug' => 'honda-pilot-2025',
                'description' => 'سيارة عائلية متعددة الاستخدامات بمساحة واسعة وراحة عالية.',
                'short_description' => 'سيارة عائلية متعددة الاستخدامات',
                'price' => 550,
                'regular_price' => 550,
                'category' => 'family-cars',
                'image' => $template_dir . '/my-car/assets/small-car.png',
                'attributes' => array(
                    'عدد الركاب' => '7',
                    'عدد الأبواب' => '5',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            
            // SUV & Crossover
            array(
                'name' => 'تويوتا راف4 2025',
                'slug' => 'toyota-rav4-2025',
                'description' => 'سيارة دفع رباعي متوسطة الحجم بتصميم عصري وأداء قوي.',
                'short_description' => 'سيارة دفع رباعي متوسطة الحجم',
                'price' => 500,
                'regular_price' => 500,
                'category' => 'suv-crossover',
                'image' => $template_dir . '/my-car/assets/product.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '5',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            array(
                'name' => 'هوندا CR-V 2025',
                'slug' => 'honda-crv-2025',
                'description' => 'سيارة كروس أوفر عملية وموثوقة، مناسبة للمدينة والطرق الوعرة.',
                'short_description' => 'سيارة كروس أوفر عملية وموثوقة',
                'price' => 480,
                'regular_price' => 480,
                'category' => 'suv-crossover',
                'image' => $template_dir . '/my-car/assets/product.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '5',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            
            // Luxury
            array(
                'name' => 'مرسيدس بنز C-Class 2025',
                'slug' => 'mercedes-benz-c-class-2025',
                'description' => 'سيارة فاخرة بتصميم أنيق ومميزات متقدمة للراحة والرفاهية.',
                'short_description' => 'سيارة فاخرة بتصميم أنيق',
                'price' => 800,
                'regular_price' => 800,
                'category' => 'luxury',
                'image' => $template_dir . '/my-car/assets/product.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '4',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
            array(
                'name' => 'بي إم دبليو الفئة الثالثة 2025',
                'slug' => 'bmw-3-series-2025',
                'description' => 'سيارة فاخرة رياضية بأداء قوي وتقنيات متقدمة.',
                'short_description' => 'سيارة فاخرة رياضية بأداء قوي',
                'price' => 850,
                'regular_price' => 850,
                'category' => 'luxury',
                'image' => $template_dir . '/my-car/assets/product.png',
                'attributes' => array(
                    'عدد الركاب' => '5',
                    'عدد الأبواب' => '4',
                    'نوع الوقود' => 'بنزين',
                    'ناقل الحركة' => 'أوتوماتيك',
                ),
            ),
        ),
    );
}
