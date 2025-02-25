<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Providers;

use App\Helpers\ByteUnits;
use App\Helpers\HiddenCaptcha;
use App\Interfaces\ByteUnitsInterface;
use App\Models\Page;
use App\Models\Torrent;
use App\Models\User;
use App\Observers\TorrentObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     */
    public function register(): void
    {
        // Hidden Captcha
        $this->app->bind('hiddencaptcha', HiddenCaptcha::class);

        // Gabrielelana byte-units
        $this->app->bind(ByteUnitsInterface::class, ByteUnits::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // User Observer For Cache
        User::observe(UserObserver::class);
        Torrent::observe(TorrentObserver::class);

        // Torrent Observer For Cache
        // Torrent::observe(TorrentObserver::class);

        // Share $footer_pages across all views
        view()->composer('*', function (View $view): void {
            $footerPages = cache()->remember('cached-pages', 3_600, fn () => Page::select(['id', 'name', 'created_at'])->take(2)->get());

            $view->with(['footer_pages' => $footerPages]);
        });

        // Hidden Captcha
        Blade::directive('hiddencaptcha', fn ($mustBeEmptyField = '_username') => sprintf('<?= App\Helpers\HiddenCaptcha::render(%s); ?>', $mustBeEmptyField));

        $this->app['validator']->extendImplicit(
            'hiddencaptcha',
            function ($attribute, $value, $parameters, $validator) {
                $minLimit = (isset($parameters[0]) && is_numeric($parameters[0])) ? $parameters[0] : 0;
                $maxLimit = (isset($parameters[1]) && is_numeric($parameters[1])) ? $parameters[1] : 1_200;

                if (!HiddenCaptcha::check($validator, $minLimit, $maxLimit)) {
                    $validator->setCustomMessages(['hiddencaptcha' => '验证失败']);

                    return false;
                }

                return true;
            }
        );
    }
}
