<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    private static array $products = [
        ['Wireless Mouse', 'Ergonomic wireless mouse with long battery life and precise tracking.'],
        ['Mechanical Keyboard', 'Compact mechanical keyboard with tactile switches and RGB backlight.'],
        ['USB-C Hub', '7-in-1 USB-C hub with HDMI, USB 3.0, SD card reader and PD charging.'],
        ['Laptop Stand', 'Adjustable aluminium laptop stand for improved ergonomics.'],
        ['Webcam HD', '1080p webcam with built-in noise-cancelling microphone.'],
        ['Monitor 27"', '27-inch IPS monitor with 144Hz refresh rate and HDR support.'],
        ['Desk Lamp', 'LED desk lamp with adjustable brightness and colour temperature.'],
        ['Cable Organiser', 'Silicone cable management clips for a tidy workspace.'],
        ['Noise-Cancelling Headphones', 'Over-ear headphones with active noise cancellation and 30h battery.'],
        ['Smart Power Strip', '6-outlet power strip with USB ports and individual surge protection.'],
        ['Portable SSD 1TB', 'Compact 1TB SSD with USB-C, up to 1050MB/s read speed.'],
        ['Wrist Rest', 'Memory foam wrist rest for keyboard and mouse comfort.'],
        ['Monitor Light Bar', 'Screen-mounted LED light bar that reduces eye strain.'],
        ['Docking Station', 'Universal docking station with dual monitor support and Ethernet.'],
        ['Microphone USB', 'Condenser USB microphone for podcasting and video calls.'],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $product = self::$products[self::$index % count(self::$products)];
        self::$index++;

        return [
            'name' => $product[0],
            'description' => $product[1],
            'price' => fake()->randomFloat(2, 9.99, 499.99),
            'stock' => fake()->numberBetween(0, 200),
        ];
    }
}
