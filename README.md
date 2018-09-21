TodoList
========

A very basic and ugly TODO-List written in PHP and using MySQL for storage.

### Important Notice


1. There is no User-Management whatsoever, everyone who has access can do everything.<br>
   Deploy it behind a HTTP Basic Auth!
2. There is no input validation happening. However:<br>
   * SQL-Injections should not be possible, prepared statements are used everywhere
   * HTML-Code in list items, names and descriptions will be escaped before printing
