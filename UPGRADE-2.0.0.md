UPGRADE FROM 1.0.1 to 2.0.0
===========================

### General

  * Universal Analytics supported

    The recommended template now supports Google Universal Analytics.

    Change the template included to be

    ```html
    {% include "GoogleBundle:Analytics:async_universal.html.twig" %}
    ```
