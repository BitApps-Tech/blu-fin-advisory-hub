# Quick Fix: Storage Symlink on cPanel

## The Problem
Files upload successfully but images return 404 errors. This is because the storage symlink is missing.

## Quick Solution (SSH)

```bash
# 1. Navigate to your API directory
cd ~/api.MamoKachapastry.com  # or your actual path

# 2. Create the storage symlink
php artisan storage:link

# 3. Set permissions (if needed)
chmod -R 755 storage/app/public
chmod 755 public
```

## Verify It Works

After running the command, test by:
1. Uploading a file in admin panel
2. Accessing: `https://api.MamoKachapastry.com/storage/uploads/2025/01/your-file.jpg`

If the image loads, you're done! ✅

## If SSH is Not Available

Use cPanel File Manager:
1. Go to `public` folder
2. Delete `storage` folder if it exists (make sure it's not a symlink first)
3. Use Terminal in cPanel (if available) to run: `php artisan storage:link`

## Full Guide
See `CPANEL_STORAGE_FIX.md` for detailed troubleshooting.

