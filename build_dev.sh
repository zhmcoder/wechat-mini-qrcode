composer require andruby/deep-admin andruby/laravel-vue-admin

rm -rf public/vendor

php artisan vendor:publish --provider="SmallRuralDog\Admin\AdminServiceProvider"
php artisan vendor:publish --provider="Andruby\DeepAdmin\AdminServiceProvider"
