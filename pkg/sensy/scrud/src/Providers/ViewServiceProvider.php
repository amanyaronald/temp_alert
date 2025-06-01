<?php

namespace Sensy\Scrud\Providers;

use App\Models\Menu;
use App\Models\Setting;
use App\Models\SystemModuleCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(
            '*',
            function ($view) {
                $settings = Setting::first();
                $view->with('sys_setting', $settings);
            }
        );

        //# Load Backend Sidebar Menus
        View::composer(
            ['scrud::components.backend.layouts.sidebar'],
            function ($view) {
                // Get menus grouped by system_module_category and ordered by position
                $menus = Menu::with([
                    'systemModule' => function ($query) {
                        $query->with([
                            'system_module_category' => function ($query) {
                                $query->where('is_active', true)
                                    ->orderBy('position', 'asc');
                            }
                        ])->where('is_active', true)
                            ->orderBy('position', 'asc');
                    }
                ])
                    ->whereHas('systemModule', function ($query) {

                        $query->whereHas('system_module_category', function ($query) {
                            $query->where('is_active', true);
                        })->where('is_active', true);
                    })
                    ->orderBy('position', 'desc')

                    ->get()
                    ->groupBy('systemModule.system_module_category.name');

                $view->with('menus', $menus);
            }
        );
    }
}
