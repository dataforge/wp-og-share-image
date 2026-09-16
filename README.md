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
header and the updater in `includes/class-ogsi-updater.php`.

### How to ship a code change (Flow A — automated)

1. Edit the code.
2. Bump `Version:` in the `wp-og-share-image.php` header — this is the
   **only** version source; the updater and the release workflow both read
   it. The release workflow will hard-fail if the tag doesn't match this.
3. Lint changed PHP: `php -l <file>` for each file touched.
4. Commit and push to `master`. Pushing alone does **not** release
   anything — only a tag does.
5. Tag and push the tag:
   ```
   git tag vX.Y.Z
   git push origin vX.Y.Z
   ```
6. GitHub Actions (`.github/workflows/release.yml`) builds the zip with
   `build_plugin.py` and publishes a GitHub Release with it attached —
   usually done in under a minute. Watch it with:
   ```
   gh run watch --repo dataforge/wp-og-share-image
   ```
7. Sites pick up the new version within `OGSI_Updater::CACHE_TTL` (12h), or
   immediately via **Plugins → wp-og-share-image → Check for Updates**
   (added automatically as a plugin action link) or **Dashboard → Updates
   → Check Again**.

Never edit and commit directly from inside a site's Docker container — those
containers have no `gh`/Python and can't create releases; always edit
locally (or wherever this repo is cloned with push access) and let CI build
the zip.
