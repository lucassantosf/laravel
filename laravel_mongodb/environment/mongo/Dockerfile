FROM mongo:4.2.8
LABEL maintainer="Lucas Santos <lucasferreira@cryptos.eti.br>"

COPY ./environment/mongo/dump/archive.gz /usr/share/archive.gz
RUN chmod +x /usr/share/archive.gz

CMD ["docker-entrypoint.sh", "mongod"]
