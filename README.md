# Forge Scholars — Drupal 10

The live preview cannot save this package. Download it from GitHub instead.

1. Open https://github.com/jonTana/forge-scholars-drupal10
2. Click the green **Code** button.
3. Click **Download ZIP**.

Then copy:

- `web/themes/custom/forge_scholars` into your Drupal site at `web/themes/custom/`
- `web/modules/custom/forge_scholars_signup` into `web/modules/custom/`

Enable **Forge Scholars signup**. That turns on the theme and sets the front page to `/forge`.

Local install, with PHP 8.1+ and Composer:

```bash
composer create-project drupal/recommended-project:^10 forge-local
cd forge-local
cp -R /path/to/unzipped/web/themes/custom/forge_scholars web/themes/custom/
cp -R /path/to/unzipped/web/modules/custom/forge_scholars_signup web/modules/custom/
php -d memory_limit=512M ./vendor/bin/drush site:install standard \
  --db-url=sqlite://sites/default/files/.ht.sqlite \
  --site-name="Forge Scholars" \
  --account-name=admin \
  --account-pass=admin \
  -y
./vendor/bin/drush en forge_scholars_signup -y
./vendor/bin/drush cr
```

Without Drush, install Drupal in the browser, then enable **Forge Scholars signup** at `/admin/modules`.

If the images folder contains `.b64` files instead of photos, run `sh scripts/restore-media.sh` once.
