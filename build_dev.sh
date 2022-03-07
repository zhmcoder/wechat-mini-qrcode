rm -rf public/vendor

php artisan vendor:publish --provider="Andruby\DeepAdmin\AdminServiceProvider"
