# Fix Media Upload Storage Issue on cPanel

## Problem
Files are uploaded successfully on localhost but images are not accessible on cPanel. Files are saved to `storage/app/public/uploads/2025/` but the images return 404 errors.

## Root Cause
Laravel requires a symbolic link from `public/storage` to `storage/app/public` to make uploaded files accessible via the web. This symlink is missing or broken on your cPanel server.

## Solution

### Method 1: Using SSH (Recommended)

1. **Connect to your cPanel server via SSH**
   ```bash
   ssh username@your-server.com
   ```

2. **Navigate to your API directory**
   ```bash
   cd ~/public_html/api  # or wherever your API is located
   # OR if using subdomain:
   cd ~/api.MamoKachapastry.com  # adjust to your subdomain path
   ```

3. **Remove existing symlink if it exists (optional)**
   ```bash
   rm public/storage
   ```

4. **Create the storage symlink**
   ```bash
   php artisan storage:link
   ```

5. **Verify the symlink was created**
   ```bash
   ls -la public/ | grep storage
   ```
   You should see: `storage -> /home/username/path/to/storage/app/public`

6. **Set proper permissions** (if needed)
   ```bash
   chmod -R 755 storage
   chmod -R 755 public/storage
   ```

### Method 2: Using cPanel File Manager

1. **Log into cPanel**

2. **Navigate to File Manager**
   - Go to `public_html/api/public` (or your API's public directory)

3. **Check if `storage` folder exists**
   - If it exists and is a regular folder, delete it first
   - If it's already a symlink, you can skip to step 5

4. **Create the symlink manually** (if File Manager supports it):
   - Some cPanel File Managers have a "Create Symlink" option
   - Source: `../storage/app/public`
   - Link name: `storage`
   - Location: `public/storage`

5. **If File Manager doesn't support symlinks**, use Method 1 (SSH)

### Method 3: Manual Symlink via Terminal in cPanel

1. **Open Terminal in cPanel**
   - Some cPanel hosts provide a Terminal option

2. **Run the same commands as Method 1**

## Verify the Fix

1. **Check if files are accessible**
   - Upload a test image via the admin panel
   - Check the database - the `path` should be something like `uploads/2025/01/filename.jpg`
   - Try accessing: `https://api.MamoKachapastry.com/storage/uploads/2025/01/filename.jpg`
   - The image should load

2. **Check Laravel logs** (if still having issues)
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Common Issues and Solutions

### Issue 1: Permission Denied
**Error:** `Permission denied` when creating symlink

**Solution:**
```bash
chmod 755 public
chmod -R 775 storage/app/public
```

### Issue 2: Symlink Created But Files Still Not Accessible
**Possible causes:**
- Web server doesn't follow symlinks
- Incorrect .htaccess configuration

**Solution:**
Check your `public/.htaccess` file includes:
```apache
Options +FollowSymLinks
```

### Issue 3: Files Upload But Return 404
**Check:**
1. Symlink exists: `ls -la public/storage`
2. Files exist: `ls -la storage/app/public/uploads/2025/`
3. Permissions are correct: `chmod -R 755 storage/app/public`

### Issue 4: Different Path Structure on cPanel
If your API is in a subdomain, the path might be:
```
/home/cpanel_username/api.MamoKachapastry.com
```

Adjust the `cd` command accordingly.

## File Structure After Fix

Your directory structure should look like this:
```
api/
├── public/
│   ├── index.php
│   └── storage -> ../storage/app/public  (symlink)
├── storage/
│   └── app/
│       └── public/
│           └── uploads/
│               └── 2025/
│                   └── 01/
│                       └── filename.jpg
```

## Testing

After creating the symlink:

1. **Upload a test file** via the admin media library
2. **Check the database** - verify the `path` is stored correctly
3. **Access the file directly** via URL:
   ```
   https://api.MamoKachapastry.com/storage/uploads/2025/01/your-file.jpg
   ```
4. **Check in admin panel** - the image should display correctly

## Additional Notes

- The `php artisan storage:link` command creates a symlink that points `public/storage` to `storage/app/public`
- Files are stored in `storage/app/public/uploads/YYYY/MM/` based on the current date
- The Media model generates URLs using `asset('storage/' . $path)`, which relies on this symlink
- If you move your Laravel installation, you'll need to recreate this symlink

## Need Help?

If you're still experiencing issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify file permissions: `ls -la storage/app/public`
3. Test symlink: `readlink public/storage`
4. Check web server error logs in cPanel

