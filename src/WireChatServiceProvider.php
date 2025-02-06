<?php

namespace Namu\WireChat;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Namu\WireChat\Console\Commands\InstallWireChat;
use Namu\WireChat\Facades\WireChat as FacadesWireChat;
use Namu\WireChat\Livewire\Chat\Chat;
use Namu\WireChat\Livewire\Chats\Chats;
use Namu\WireChat\Livewire\Components\NewChat;
use Namu\WireChat\Livewire\Components\NewGroup;
use Namu\WireChat\Livewire\Group\Permissions;
use Namu\WireChat\Livewire\Index;
use Namu\WireChat\Livewire\Info\AddMembers;
use Namu\WireChat\Livewire\Info\Info;
use Namu\WireChat\Livewire\Info\Members;
use Namu\WireChat\Livewire\Modals\ChatDialog;
use Namu\WireChat\Livewire\Modals\ChatDrawer;
use Namu\WireChat\Livewire\View;
use Namu\WireChat\Livewire\Widgets\WireChat;
use Namu\WireChat\Middleware\BelongsToConversation;
use Namu\WireChat\Services\WireChatService;
use Namu\WireChat\View\Components\ChatBox\Image;

class WireChatServiceProvider extends ServiceProvider
{
    public function boot()
    {

        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallWireChat::class,
            ]);
        }

        $this->loadLivewireComponents();

        Blade::component('wirechat::chatbox.image', Image::class);

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wirechat');

        //publish config
        $this->publishes([
            __DIR__.'/../config/wirechat.php' => config_path('wirechat.php'),
        ], 'wirechat-config');

        //publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'wirechat-migrations');

        //publish views
        if ($this->app->runningInConsole()) {
            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/wirechat'),
            ], 'wirechat-views');

        }

        /* Load channel routes */
        $this->loadRoutesFrom(__DIR__.'/../routes/channels.php');

        //load assets
        $this->loadAssets();

        //load styles
        $this->loadStyles();

        //load middleware
        $this->registerMiddlewares();

    }

    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__.'/../config/wirechat.php', 'wirechat'
        );

        //register facades
        $this->app->singleton('wirechat', function ($app) {
            return new WireChatService;
        });

    }

    //custom methods for livewire components
    protected function loadLivewireComponents()
    {
        Livewire::component('index', Index::class);
        Livewire::component('view', View::class);

        Livewire::component('chat', Chat::class);
        Livewire::component('chats', Chats::class);

        //wirechat  modal
        Livewire::component('chat-dialog', ChatDialog::class);
        Livewire::component('chat-drawer', ChatDrawer::class);

        Livewire::component('new-chat', NewChat::class);

        //Group related components
        Livewire::component('new-group', NewGroup::class);
        Livewire::component('info', Info::class);
        Livewire::component('add-members', AddMembers::class);
        Livewire::component('members', Members::class);
        Livewire::component('permissions', Permissions::class);

        //Widgets
        Livewire::component('wirechat', WireChat::class);

    }

    protected function registerMiddlewares()
    {

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('belongsToConversation', BelongsToConversation::class);

    }

    //load assets
    protected function loadAssets()
    {

        Blade::directive('wirechatAssets', function () {
            return "<?php 
                echo Blade::render('@livewire(\'chat-dialog\')');
                echo Blade::render('<x-wirechat::toast/>');
                ?>";
        });
    }

    //load assets
    protected function loadStyles()
    {

        $primaryColor = FacadesWireChat::getColor();
        Blade::directive('wirechatStyles', function () use ($primaryColor) {
            return "<?php echo <<<EOT
                <style>
                    :root {
                        --wirechat-primary-color: {$primaryColor};
                    }
                </style>
            EOT; ?>";
        });
    }
}
