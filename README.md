# Jumping On The Clouds

## Requirements
This project requires you to have installed Docker Desktop or Docker plus Docker Compose.

## Running
Run the following command in the root directory
    
    docker compose up

The docker compose stack will expose the following services (services marked as GUI are web based administration interfaces):
| Service            | URL                                     |
| ------------------ | --------------------------------------- |
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