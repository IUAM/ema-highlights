<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Entry;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * To reduce database requests, we cache some properties here.
     */
    private $entry;

    private $categories;

    private $currentCategoryIds;

    private $openCategoryIds;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer([
            'entry',
            'category-top',
        ], function($view) {
            $view->with('sidebar', $this->getSidebar());
        });

        View::composer('entry', function($view) {
            $view->with('breadcrumbs', $this->getBreadcrumbs());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Helper method to prevent needless queries.
     */
    private function getEntry()
    {
        return $this->entry ?? $this->entry = Entry::findOrFail(Route::current()->parameter('id'));
    }

    private function getCategories()
    {
        return $this->categories ?? $this->categories = Category::all();
    }

    private function getCurrentCategoryIds()
    {
        return $this->currentCategoryIds ?? $this->currentCategoryIds = $this->initCurrentCategoryIds();
    }

    private function getOpenCategoryIds()
    {
        return $this->openCategoryIds ?? $this->openCategoryIds = $this->initOpenCategoryIds();
    }

    /**
     * Retrieve category collection, nest them via `children`, and transform them
     * into a nested array suitable for use in the `sidebar` view.
     */
    private function getSidebar()
    {
        // Tranform our flat category collection into a nested array
        $categories = $this->getCategories();
        $nestedCategories = Category::getAllNested($categories);

        // Transform nested array to have only the fields we need to render each item
        $sidebar = $this->getSidebarItems($nestedCategories);

        return $sidebar;
    }

    /**
     * Separating this method out due to its recursive nature.
     */
    private function getSidebarItems($nestedCategories)
    {
        foreach ($nestedCategories as $category)
        {
            $sidebarItems[] = [
                'title' => $category->title_medium_safe,
                'href' => route('category', ['id' => $category->id]),
                'is_current' => in_array($category->id, $this->getCurrentCategoryIds()),
                'is_open' => in_array($category->id, $this->getOpenCategoryIds()),
                'children' => $this->getSidebarItems($category->children),
            ];
        }

        return $sidebarItems ?? [];
    }

    /**
     * For now, this assumes all open categories are in the same tree, i.e. that the
     * entry is only within one category. This may not always be the case.
     */
    private function getBreadcrumbs()
    {
        $categories = $this->getCategories();

        foreach($this->getOpenCategoryIds() as $category_id)
        {
            $breadcrumbs[] = [
                "id" => $category_id,
                "href" => route('category', ['id' => $category_id]),
                "title" => $categories->firstWhere('id', $category_id)->title,
            ];
        }

        // Add this entry as the final breadcrumb
        $entry = $this->getEntry();

        $breadcrumbs[] = [
            "id" => $entry->id,
            "href" => route('entry', ['id' => $entry->id]),
            "title" => $entry->accession,
        ];

        return $breadcrumbs;
    }

    /**
     * This function determines which sidebar items represent the current categories.
     *
     * Currently, this determines which `<a/>` items get the `.active` class applied
     * to them, which gives them a small, off-gray triangle indicator.
     *
     * Entries might be located in multiple categories. Multiple categories may
     * qualify as "current" at the same time.
     *
     * This returns an array of category ids.
     *
     * @return array[integer]
     */
    private function initCurrentCategoryIds()
    {
        if (Route::is('entry'))
        {
            $entry = $this->getEntry();

            return $entry->categories()->get([
                'categories.id',
                'categories.category_id'
            ])->pluck('id')->all();
        }

        if (Route::is('category'))
        {
            return [Route::current()->parameter('id')];
        }

        return [];
    }

    /**
     * For ancestors of the current category, we need to set `.active` on the `<ul/>`, but not the `<a/>`.
     */
    private function initOpenCategoryIds()
    {
        foreach ($this->getCurrentCategoryIds() as $current_category_id)
        {
            do
            {
                $active_category_ids[] = $current_category_id;
                $current_category_id = $this->getCategories()->firstWhere('id', $current_category_id)->category_id;
            } while(isset($current_category_id));
        }

        return array_reverse($active_category_ids);
    }

}
