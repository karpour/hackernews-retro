## Hacker news interface for old computers

While Hacker News doesn't make use of fancy JS and layouts, it still doesn't display properly on very old browsers. This php script aims to solve this tragedy.

Apart from displaying the main site using pure HTML4, it also extracts article data using readability.php and replaces all links on the websites so they also get converted by this script. This will break a lot of websites, but since most links posted on Hacker News are articles and most websites won't display properly anyway, it's a good compromise if you want to read articles on Windows 95 or Windows CE.

Planned feature: image transcoding and resizing

## Usage

```
composer install
php -S localhost:8000
```