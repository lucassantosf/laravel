FROM node:10-alpine

WORKDIR /app

COPY environment/websocket/package.json environment/websocket/server.js /app/
COPY .env /app/.env

RUN npm -g install