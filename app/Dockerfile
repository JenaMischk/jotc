FROM node as node
WORKDIR /app
COPY . .
RUN npm install
RUN npm run build --prod

FROM nginx
COPY ./default.conf /etc/nginx/conf.d/default.conf
COPY --from=node /app/dist/app /usr/share/nginx/html