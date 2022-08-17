FROM node:latest

WORKDIR /usr/src/app

COPY ./svelte/package.json ./

RUN yarn install

CMD ["yarn", "dev"]
