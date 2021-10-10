rm -rf public/vendor/laravel-vue-admin

cd packages/Andruby/vue-element-admin
git checkout .
git pull
npm install
npm run production

cd ..
cd ..
cd ..
php artisan vendor:publish --provider="SmallRuralDog\Admin\AdminServiceProvider"

cd packages/Andruby/deep-admin
git checkout .
git pull
npm install
npm run production

cd ..
cd ..
cd ..
php artisan vendor:publish --provider="Andruby\DeepAdmin\AdminServiceProvider"
