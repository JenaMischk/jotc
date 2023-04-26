# Jumping On The Clouds

## Requirements
This project requires you to have installed Docker Desktop or Docker plus Docker Compose. You should also replace the test Emailable API Key for a live one in the .env file on the root directory.
## Running
Run the following command in the root directory
    
    docker compose up

The docker compose stack will expose the following services (services marked as GUI are web based administration interfaces):
| Service            | URL                                     |
| ------------------ | --------------------------------------- |
| Frontend (app)     | [localhost(:80)](http://localhost)      |
| API                | [localhost:4000](http://localhost:4000) |
| Database           | [localhost:5432](http://localhost:5432) |
| Redis              | [localhost:6379](http://localhost:6379) |
| Adminer (GUI)      | [localhost:8080](http://localhost:8080) |
| RedisInsight (GUI) | [localhost:8001](http://localhost:8001) |
| Locust (GUI)       | [localhost:8089](http://localhost:8089) |

## Architecture
### Repository and Overall Deployment
This project is currently being managed as a monolithic version control repository for ease of use but its intended usage is as a workspace for creating multiple independent services. This can be achieved by spliting this repository into multiple repositories, one for each project subdirectory (api, tests, db). Each of these subdirectories contains an isolated Dockerfile and source code for that particular piece of the stack. This means that all services can be independently deployed and scaled using, for example, Kubernetes, achieving a scalable and fault tolerant system.
### Web Server
The webserver for this project is [RoadRunner](https://roadrunner.dev/). RoadRunner is distributed as a single binary and works as an application server, load-balancer, and process manager. For this particular project's setup RoadRunner creates a worker for each thread of the allocated CPU, which then keeps PHP code running in a loop (daemonized), never loading code or initializing dependencies twice and featuring persistent database and Redis connections, massively speeding up response times and overall application performance. This means that initial requests will be slower while they are distributed to different workers that have not been initialized yet, and subsequent requests to initialized workers will be much faster. This also means that this performance can be further boosted in production deployments if all independent services are hosted on the same virtual network (local network calls and persistent connections make up the larger part of the performance boost of this architecture design). As an example, on my personal computer, running an Intel i7 8700 (6 cores, 12 threads), I get 12 workers that take about 350ms on average for the first request and 4ms on average for the subsequent requests (including database and Redis roundtrip times). I have included Locust to load test the API, which should be exposed on [localhost:8089](http://localhost:8089).
## TODO
The project currently features no way to switch between development and production modes automatically (features only environment variable reading) and therefore always runs with the same verbosity. Both the App and API must be refactored to allow loading different settings for different environments.  
Better state management for the database and Redis connections on the API is required. There must be a connection retry in case of connection or query execution failure, as if it is not present, that connection will be unusable for the rest of that worker's lifespan. The web server will eventually detect that the worker is not producing the desired output and will replace it with a fresh one, but that is an operation that is more costly than retrying a connection for an existing worker.  
The API is currently not handling errors properly. Currently all thrown exceptions cause the execution flow to be interrupted and middleware to not be ran. This in turn means that the CORS Middleware will not set the appropriate response headers and the browser will incorrectly report error responses as CORS blocked calls. The ideal solution would be to catch all exceptions with an Error Handler Middleware, add them to the request attributes, receive them at the last executing middleware and convert them to a regular JSON response. In past implementations I have returned 16 character error message hashes in the response to allow the final user to be displayed a toaster with a readable code that can be sent out to support and very easily found in the logs.  
There is some work left in the auth JWT token validation, as it is not considering expiration times in the API.  
Loggin has been disconsidered.  
