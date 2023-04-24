from locust import task, FastHttpUser

class HelloWorldUser(FastHttpUser):
    host = "http://api:4000"
    @task
    def hello_world(self):
        self.client.post("/login", json = {"email": "p.ribeiro.hey@gmail.com", "firstName" : "Pedro", "lastName" : "Ribeiro", "birthDate" : "1996-03-26"})