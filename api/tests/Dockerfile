FROM python:3

WORKDIR /usr/src/tests

RUN pip install locust

COPY . .

CMD locust -f load.py