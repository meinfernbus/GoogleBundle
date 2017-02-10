UPGRADE FROM 1.0.0 to 1.0.1
===========================

### General

  * Session Serialization has changed from Object based to array based. 

    This allows us to explore less traditional session storage strategies.
    The upgrade is backwards compatibile and will recognize and handle 
    Google Analytics objects previously in session.

    No action should be necessary.

  * An additional configuration has been included:

    ```yaml
    google:
        analytics:
            session_auto_started: true  
    ```

    For integrations where the session is automatically started by the
    application, this configuration should be set to true.
