
## How to deploy

1. clone this repo
2. `cd abz-test/`
3. `./configure`
6. `./sail up -d`
7. `./sail npm i && ./sail npm run build`
8. `./sail restart`
9. open http://localhost:8080/ or https://localhost:443/ in browser

[//]: # (TODO: add dummy data for db in order to allow it deploy on PwD service)
[//]: # (Try it on Play with Docker playground)
[//]: # ([![Try in PWD]&#40;https://raw.githubusercontent.com/play-with-docker/stacks/master/assets/images/button.png&#41;]&#40;https://labs.play-with-docker.com/?stack=https://raw.githubusercontent.com/santaklouse/onlygirls-test/main/docker-compose.yml&#41;)
