name the link address in xampp apache server as below

Alias /web "your_folder_address"
<Directory "your_folder_address">
Options Indexes FollowSymLinks MultiViews
AllowOverride All
Require all granted
</Directory>
