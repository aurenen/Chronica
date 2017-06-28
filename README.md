*Note:* Not ready for public use. Download at your own risk.

# Chronica

Chronica is a simple blogging script, best used for categorized updates. There's no extra flare like entry tagging and commenting, and it only uses markdown for content formatting.

## Requirements

You need a recent version of PHP and MySQL, preferably at least `PHP 5.5` and `MySQL 5.5`. You should have a database just for Chronica, and not mix other scripts' access in the same database to avoid table name conflicts (table prefix options may be available in future versions).

## Installation

1. Download the zip
2. Extract locally
3. Modify contents of `/includes/config.php`
4. Upload to your desired location
5. Open `/install/` from the web and follow instructions

## Usage

- Place your template files inside `/template/`
- Edit `/template/header.php` and `/template/footer.php` to what your layout requires.
- If you want to change the way the entries are displayed, edit `view.php`