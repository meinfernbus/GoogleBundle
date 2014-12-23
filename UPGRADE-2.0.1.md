UPGRADE FROM 2.0.0 to 2.0.1
===========================

### General

  * An additional configuration has been included to attach plugins:

    ```yaml
    google:
        analytics:
            trackers:
                default:
                    plugins:
            	        - 'linkid'
    ```
