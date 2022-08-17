FROM node:latest

WORKDIR /usr/src/app

COPY ./react/package.json ./

RUN yarn install

CMD ["yarn", "dev"]
