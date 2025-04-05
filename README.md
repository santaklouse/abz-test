
## How to deploy

1. clone this repo
2. `cd abz-test/`
3. `./configure`
4. `./sail up -d`
5. `./sail artisan migrate:fresh --seed`
6. `./sail pnpm install`
7. `./sail pnpm run build`
8. `./sail artisan  storage:link`
10. `./sail restart`
11. open http://localhost:8080/ or https://localhost:443/ in browser

[//]: # (TODO: add dummy data for db in order to allow it deploy on PwD service)
[//]: # (Try it on Play with Docker playground)
[//]: # ([![Try in PWD]&#40;https://raw.githubusercontent.com/play-with-docker/stacks/master/assets/images/button.png&#41;]&#40;https://labs.play-with-docker.com/?stack=https://raw.githubusercontent.com/santaklouse/onlygirls-test/main/docker-compose.yml&#41;)
