<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use DOMDocument;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*DB::beforeExecuting(function($query){
            echo  "<pre>$query</pre>";
        });*/

        Blade::directive('svg', function ($arguments) {
            try {
                // Funky madness to accept multiple arguments into the directive
                list($path, $class) = array_pad(explode(',', trim($arguments, "() ")), 2, '');
                $path = trim($path, "' ");
                $class = trim($class, "' ");
                // Create the dom document as per the other answers
                $svg = new DOMDocument();
                $svg->load(public_path($path));
                $svg->documentElement->setAttribute("class", $class);
                $output = $svg->saveXML($svg->documentElement);
                return $output;
            } catch (Exception $e) {
                echo "<h3 style='color:red'>Возможно неверный путь к SVG иконке</h3>";
                die;
//                echo "<h3>__FILE__</h3>";
//                die($e);
            }
        });
    }
}
