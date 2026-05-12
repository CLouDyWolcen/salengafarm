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
        
        if (str_contains($host, 'esthersgarden') || str_contains($host, 'esthers-garden')) {
            return "Esther's Garden";
        }
        
        return 'Salenga Farm';
    }
    
    /**
     * Get the logo path based on current domain
     */
    public static function getLogo()
    {
        $host = request()->getHost();
        
        if (str_contains($host, 'esthersgarden') || str_contains($host, 'esthers-garden')) {
            return asset('images/esthersgarden-modified.png');
        }
        
        return asset('images/salengap-modified.png');
    }
    
    /**
     * Check if current site is Esther's Garden
     */
    public static function isEsthersGarden()
    {
        $host = request()->getHost();
        return str_contains($host, 'esthersgarden') || str_contains($host, 'esthers-garden');
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
            return 'Discover our wide range of available plants';
        }
        
        return 'Discover our wide range of available plants';
    }
}
