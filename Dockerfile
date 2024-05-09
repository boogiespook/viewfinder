FROM registry.access.redhat.com/ubi9/php-81:latest
MAINTAINER Chris Jenkins "chrisj@redhat.com"
EXPOSE 8080
COPY . /opt/app-root/src
CMD /bin/bash -c 'php -S 0.0.0.0:8080'
