FROM node:latest

WORKDIR /usr/src/app

COPY ./svelte/package.json ./svelte/yarn.lock ./

RUN yarn install

CMD ["yarn", "dev"]
