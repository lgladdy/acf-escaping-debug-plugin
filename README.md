## ACF Escaping Debug Plugin

In ACF 6.2.5, the plugin began warning users whenever it detected the value being output was not correctly escaped when using the ACF shortcode, `the_field` or `the_sub_field`

In ACF 6.2.7 due for release in February 2024, ACF will automatically begin escaping the value when `the_field` or `the_sub_field` is used.

ACF will show a warning notice in WordPress admin for this detection, but for performance reasons this notice is limited to the name of the field, it's selector used to output the value, and the function called.

This plugin extends that default logging to output a more detailed version whenever it is detected. The output is sent to the PHP error log via `error_log`.

This plugin should not be used on production, as it has potential to generate a log of error log messages.

### Example Output:

```
[18-Jan-2024 15:36:49 UTC] ***ACF HTML Escaping Debug***
HTML modification detected the value of wysiwyg on post ID 140 via the_sub_field
Raw Value: '<p><script>console.log("Repeater Row 3");</script></p>
'
Escaped Value: '<p>console.log(&#8220;Repeater Row 3&#8221;);</p>
'
Template: /Users/liam/Code/local-wp/site.local/wp-content/themes/twentytwentyone/page.php
```
