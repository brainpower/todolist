TodoList
========

A very basic and ugly TODO-List written in PHP and using MySQL for storage.

### Important Notice


1. There is no User-Management whatsoever, everyone who has access can do everything.<br>
   Deploy it behind a HTTP Basic Auth!
2. There is no input validation happening. However:<br>
   * SQL-Injections should not be possible, prepared statements are used everywhere
   * HTML-Code in list items, names and descriptions will be escaped before printing

### Installation

- Put recent versions of jquery, fontawesome and solid in `public/js/3rdparty/` and `public/css/3rdparty/`
  and change the symlinks to point to them.
- Create a database and create the tables as specified in `database-schema.sql`.
- Edit `config.inc.php` and insert MySQL credentials.
