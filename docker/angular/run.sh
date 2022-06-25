#!/command/with-contenv sh
set -e;

if [ "$APP_ENV" != 'prod' ]; then
    cd /app/client
    yarn install
    yarn start
fi
