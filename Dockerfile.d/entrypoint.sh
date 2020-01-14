#!/bin/sh

DOC_ROOT=/data/wwwroot/chart.oa.com

# log directory
rm -rf $DOC_ROOT/logs
ln -sf /data/logs/ $DOC_ROOT/logs

# run as nginx
chown -R nginx:nginx $DOC_ROOT
chmod -R 777 /data/logs

# parent container startup script
chmod +x /start.sh
/start.sh
