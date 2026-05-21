<?php

namespace App\Helpers;

class BrandHelper
{
    /**
     * Get the brand name based on current domain
     */
    public static function getName()
    {
        $host = request()->getHost();
        
        if (str_contains($host, 'esthers') || str_contains($host, 'esthers-flower-garden')) {
            return "Esther's Flower Garden";
        }
        
        return 'Salenga Farm';
    }
    
    /**
     * Get the logo path based on current domain
     */
    public static function getLogo()
    {
        $host = request()->getHost();
        
        if (str_contains($host, 'esthersflowergarden') || str_contains($host, 'esthers-flower-garden')) {
            return asset('images/esthersflowergarden-modified.png');
        }
        
        return asset('images/salengap-modified.png');
    }
    
    /**
     * Check if current site is Esther's Flower Garden
     */
    public static function isEsthersGarden()
    {
        $host = request()->getHost();
        return str_contains($host, 'esthers') || str_contains($host, 'esthers-flower-garden');
    }
    
    /**
     * Get welcome message
     */
    public static function getWelcomeMessage()
    {
        return 'Welcome to ' . self::getName();
    }
    
    /**
     * Get tagline
     */
    public static function getTagline()
    {
        if (self::isEsthersGarden()) {
            return 'Flower Garden & Landscaping Services';
        }
        
        return 'Discover our wide range of available plants';
    }
    
    /**
     * Get splash page content type
     */
    public static function getSplashType()
    {
        // Both brands now use 'about' type with full content
        return 'about';
    }
    
    /**
     * Get welcome content for Salenga Farm
     */
    public static function getWelcomeContent()
    {
        return [
            'title' => 'Welcome to Salenga Farm',
            'subtitle' => 'Plants, Landscaping, and Outdoor Solutions',
            'description' => 'Find quality plants, request landscaping services, and manage your inquiries with ease through our integrated platform.',
            'features' => [
                ['icon' => 'fa-seedling', 'text' => 'Wide Variety of Plants'],
                ['icon' => 'fa-home', 'text' => 'Landscaping & Site Visits'],
                ['icon' => 'fa-clipboard-list', 'text' => 'Easy RFQ & Inquiries'],
                ['icon' => 'fa-leaf', 'text' => 'Plant Care Guidance']
            ]
        ];
    }
    
    /**
     * Get about us content for Esther's Flower Garden
     */
    public static function getAboutContent()
    {
        return [
            'title' => "Esther's Flower Garden and Landscaping Services",
            'sections' => [
                'about' => [
                    'title' => 'About Us',
                    'content' => 'We are one of the leading provider of innovations and high-quality landscaping services for residential and commercial properties. With 40 years of experience in the industry, we take pride in our ability to transform ordinary outdoor spaces into extraordinary ones that are beautiful but also functional and sustainable. Our work should not only enhance the beauty of your property but also contribute to the health and well-being of the environment.'
                ],
                'services' => [
                    'title' => 'Our Services',
                    'items' => [
                        [
                            'icon' => 'fa-ruler-combined',
                            'name' => 'Landscape Design & Build',
                            'description' => 'This involves designing outdoor spaces by selecting and arranging plants, trees, hardscapes, and other elements to create an attractive and functional outdoor environment.'
                        ],
                        [
                            'icon' => 'fa-spa',
                            'name' => 'Planting and Gardening',
                            'description' => 'This involves selecting and planting flowers, shrubs, and other plants to enhance the beauty of the landscape.'
                        ],
                        [
                            'icon' => 'fa-store',
                            'name' => 'Supply of Ornamental Products',
                            'description' => 'Enhance your outdoor and indoor spaces with our Ornamental Products, from decorative plants, elegant pots, and custom planters to garden accents and architectural features.'
                        ],
                        [
                            'icon' => 'fa-leaf',
                            'name' => 'Lawn Care',
                            'description' => 'This includes mowing, fertilizing, and aerating lawns to maintain their health and appearance.'
                        ]
                    ]
                ],
                'contact' => [
                    'title' => 'For Consultation and Get a Free Estimate',
                    'email' => 'esthersaflangarden@gmail.com',
                    'phones' => [
                        '0936-8070866 (TM)',
                        '0916-9401197 (TM)',
                        '(082) 285-8565'
                    ],
                    'facebook' => "Esther's Flower Garden and Landscaping Services",
                    'facebook_url' => 'https://facebook.com/esthersflowergarden',
                    'address' => 'Front of Fatima Vill., McLeod St. Brgy Daliao Toril, Davao City, 8025'
                ]
            ]
        ];
    }
}
