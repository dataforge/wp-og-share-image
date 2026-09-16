# WP OG Share Image

Outputs `og:image` and other Open Graph tags for posts and WooCommerce
products, using the post's native featured image (or the product's main
image) and excerpt, so Facebook and other platforms use them as the share
preview.

Complementary to `wp-external-featured-image`, which only handles posts
using an *external* image as a stand-in for a featured image and explicitly
skips posts that already have a native `_thumbnail_id` set. This plugin
covers that native-featured-image case (and WooCommerce products), so the
two can run side by side without conflicting.

## What it does

On any singular post, page, or WooCommerce product with a featured/main
image set, outputs in `wp_head`:

- `og:image`, `og:image:width`, `og:image:height`
- `og:title`
- `og:description` (post excerpt / WooCommerce short description, falling
  back to a trimmed content snippet)
- `og:url`
- `og:type`

## Updates

Self-updates from GitHub Releases on this repo — no WordPress.org listing.
Sites running this plugin poll `releases/latest` via the `Update URI`
header and the updater in `includes/class-ogsi-updater.php`. Pushing a
`vX.Y.Z` tag (`.github/workflows/release.yml`) builds and publishes a new
release automatically.
