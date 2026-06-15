FROM nginx:stable-alpine

# Copy public files for static serving
COPY ./public /var/www/public

# Copy custom nginx configuration
COPY ./environment/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./environment/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN chmod 755 -R /var/www/public

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
