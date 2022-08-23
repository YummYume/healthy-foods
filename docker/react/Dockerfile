FROM node:latest

WORKDIR /usr/src/app

COPY ./react/package.json ./react/yarn.lock ./

RUN yarn install

CMD ["yarn", "dev"]
