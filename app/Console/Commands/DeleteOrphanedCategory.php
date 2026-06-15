<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class DeleteOrphanedCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:delete {slug : The category slug to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a category by its slug';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slug = $this->argument('slug');
        
        $category = Category::where('slug', $slug)->first();
        
        if (!$category) {
            $this->error("Category with slug '{$slug}' not found.");
            return 1;
        }
        
        $this->info("Found category: {$category->name} (ID: {$category->id}, Slug: {$category->slug})");
        
        if ($this->confirm("Are you sure you want to delete this category?")) {
            $category->delete();
            $this->info("Category '{$category->name}' has been deleted successfully.");
            return 0;
        }
        
        $this->info("Deletion cancelled.");
        return 0;
    }
}
