<?php

namespace Database\Seeders;

use App\Models\Content;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Header Content
            [
                'key' => 'header_logo_text',
                'group' => 'header',
                'type' => 'text',
                'value' => 'Iconvert Pro',
                'label' => 'Logo Text',
                'description' => 'Text displayed in the header logo',
            ],
            [
                'key' => 'header_tagline',
                'group' => 'header',
                'type' => 'text',
                'value' => 'Transform Your Documents',
                'label' => 'Header Tagline',
                'description' => 'Tagline displayed in the header',
            ],
            [
                'key' => 'nav_home',
                'group' => 'header',
                'type' => 'text',
                'value' => 'Home',
                'label' => 'Navigation: Home',
                'description' => 'Home link text in navigation',
            ],
            [
                'key' => 'nav_pricing',
                'group' => 'header',
                'type' => 'text',
                'value' => 'Pricing',
                'label' => 'Navigation: Pricing',
                'description' => 'Pricing link text in navigation',
            ],
            [
                'key' => 'nav_login',
                'group' => 'header',
                'type' => 'text',
                'value' => 'Login',
                'label' => 'Navigation: Login',
                'description' => 'Login link text in navigation',
            ],
            [
                'key' => 'nav_register',
                'group' => 'header',
                'type' => 'text',
                'value' => 'Get Started',
                'label' => 'Navigation: Register',
                'description' => 'Register link text in navigation',
            ],
            [
                'key' => 'nav_dashboard',
                'group' => 'header',
                'type' => 'text',
                'value' => 'Dashboard',
                'label' => 'Navigation: Dashboard',
                'description' => 'Dashboard link text in navigation',
            ],

            // Landing Page Content
            [
                'key' => 'hero_title',
                'group' => 'landing',
                'type' => 'text',
                'value' => 'Transform Your Documents with AI Power',
                'label' => 'Hero Title',
                'description' => 'Main headline on the landing page',
            ],
            [
                'key' => 'hero_subtitle',
                'group' => 'landing',
                'type' => 'text',
                'value' => 'Convert, edit, and manage your documents with our powerful suite of tools. Fast, secure, and easy to use.',
                'label' => 'Hero Subtitle',
                'description' => 'Subtitle under the main hero title',
            ],
            [
                'key' => 'hero_cta_primary',
                'group' => 'landing',
                'type' => 'text',
                'value' => 'Start Converting Now',
                'label' => 'Primary CTA Button',
                'description' => 'Text for the primary call-to-action button',
            ],
            [
                'key' => 'hero_cta_secondary',
                'group' => 'landing',
                'type' => 'text',
                'value' => 'View Pricing',
                'label' => 'Secondary CTA Button',
                'description' => 'Text for the secondary call-to-action button',
            ],
            [
                'key' => 'features_title',
                'group' => 'landing',
                'type' => 'text',
                'value' => 'Powerful Features for Modern Document Management',
                'label' => 'Features Section Title',
                'description' => 'Title for the features section',
            ],
            [
                'key' => 'features_subtitle',
                'group' => 'landing',
                'type' => 'text',
                'value' => 'Everything you need to handle documents efficiently',
                'label' => 'Features Section Subtitle',
                'description' => 'Subtitle for the features section',
            ],

            // Footer Content
            [
                'key' => 'footer_description',
                'group' => 'footer',
                'type' => 'text',
                'value' => 'Transform your document workflow with our powerful conversion tools. Fast, secure, and reliable.',
                'label' => 'Footer Description',
                'description' => 'Description text in the footer',
            ],
            [
                'key' => 'footer_copyright',
                'group' => 'footer',
                'type' => 'text',
                'value' => '© 2024 IConvert Pro. All rights reserved.',
                'label' => 'Copyright Text',
                'description' => 'Copyright notice in the footer',
            ],
            [
                'key' => 'footer_links_privacy',
                'group' => 'footer',
                'type' => 'text',
                'value' => 'Privacy Policy',
                'label' => 'Footer Link: Privacy',
                'description' => 'Privacy policy link text',
            ],
            [
                'key' => 'footer_links_terms',
                'group' => 'footer',
                'type' => 'text',
                'value' => 'Terms of Service',
                'label' => 'Footer Link: Terms',
                'description' => 'Terms of service link text',
            ],
            [
                'key' => 'footer_links_contact',
                'group' => 'footer',
                'type' => 'text',
                'value' => 'Contact Us',
                'label' => 'Footer Link: Contact',
                'description' => 'Contact us link text',
            ],

            // General Content
            [
                'key' => 'site_name',
                'group' => 'general',
                'type' => 'text',
                'value' => 'IConvert Pro',
                'label' => 'Site Name',
                'description' => 'The name of the website/application',
            ],
            [
                'key' => 'site_description',
                'group' => 'general',
                'type' => 'text',
                'value' => 'Professional document conversion and management tools',
                'label' => 'Site Description',
                'description' => 'Short description of the website',
            ],
            [
                'key' => 'meta_title_home',
                'group' => 'general',
                'type' => 'text',
                'value' => 'IConvert Pro - Transform Your Documents with AI',
                'label' => 'Meta Title (Home)',
                'description' => 'SEO title for the home page',
            ],
            [
                'key' => 'meta_description_home',
                'group' => 'general',
                'type' => 'text',
                'value' => 'Convert, edit, and manage your documents with our powerful AI-powered tools. Fast, secure, and easy to use document conversion suite.',
                'label' => 'Meta Description (Home)',
                'description' => 'SEO description for the home page',
            ],
        ];

        foreach ($contents as $content) {
            Content::create($content);
        }
    }
}
