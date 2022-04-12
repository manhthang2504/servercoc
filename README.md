# Server Command and Control (COC)

This app will allow remote admin to control the webserver and its Laravel based project via API call.

It will:
- Deploy new project
- Setup Nginx config for new project
- Clear cache
- Install composer package
- storage:link


Note:
- In order to run, the app must have permission for respective folder (ie. /var, /var/repo, /var/www)