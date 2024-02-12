let player;
        let obstacles = [];
        let score = 0;
        let gameActive = false;
        let startTime = 0;
        let pausedTime = 0;
        let bgMusic;
        let paused = false;

        function preload() {
            player_walk_1 = loadImage('entities/run1.png');
            player_walk_2 = loadImage('entities/run2.png');
            player_jump = loadImage('entities/barajump.png');
            jumpSound = loadSound('assets/jump.mp3');
            bgMusic = loadSound('assets/music.wav');
            snail_1 = loadImage('entities/snail1.png');
            snail_2 = loadImage('entities/snail2.png');
            fly_1 = loadImage('entities/fly1.png');
            fly_2 = loadImage('entities/fly2.png');
            exitButton = loadImage('entities/exit.png');
            idle = loadImage('entities/idle.png');
            bgclouds = loadImage('entities/bgclouds.png');
            bground = loadImage('entities/bground.png');
            pixeltype = loadFont('assets/Pixeltype.ttf');
        }
  
        function setup() {
            createCanvas(350, 590);
            textFont(pixeltype);
            gameActive = false;
            startTime = 0;
            score = 0;
            pausedTime = 0;
            bgMusic.loop();
            let exitButton = document.getElementById('exitButton');
            exitButton.style.display = 'none';
            console.log("Setup function called");
        }

        function draw() {
            background(0);

            if (gameActive) {
                let canvasWidth = 1390;
                let canvasHeight = 900;

                let cloudWidth = canvasWidth; // Set cloud width to canvas width
                let cloudHeight = (bgclouds.height * cloudWidth) / bgclouds.width; // Maintain aspect ratio

                let groundWidth = canvasWidth; // Set ground width to canvas width
                let groundHeight = (bground.height * groundWidth) / bground.width; // Maintain aspect ratio

                let offsetY = 410; 
                let pauseButton = document.getElementById('pauseButton');
                pauseButton.style.display = 'block';

                let exitButton = document.getElementById('exitButton');
                exitButton.style.display = 'block';

                // Set canvas size
                resizeCanvas(canvasWidth, canvasHeight);

                // Draw bgclouds and bground with resized dimensions
                image(bgclouds, 0, 0, cloudWidth, cloudHeight); // Draw bgclouds without stretching
                image(bground, 0, offsetY, groundWidth, groundHeight);
                displayScore(pausedTime);

                player.show();
                player.update();
                

                if (frameCount % 90 === 0) { // Change 90 to the interval at which you want to spawn obstacles
                    let rand = random(1);
                    let type = rand > 0.5 ? 'fly' : 'snail'; // Randomly choose between fly and snail

                    obstacles.push(new Obstacle(type));
                }

                for (let i = obstacles.length - 1; i >= 0; i--) {
                    obstacles[i].show();
                    obstacles[i].update();

                    if (obstacles[i].offscreen()) {
                        obstacles.splice(i, 1);
                    }

                    if (player.collides(obstacles[i])) {
                        gameActive = false;
                    }
                }

            } else {
                image(idle, width / 2 - idle.width / 2, height / 2 - idle.height / 2);
                obstacles = [];
                player = new Player();
                scoreMessage = `Your score: ${score}`;
                textSize(50);
                fill(111, 196, 169);
                textAlign(CENTER, CENTER);
                text('PLAY?', width / 2, 150);
                text('Press to run', width / 2, 430);
                let pauseButton = document.getElementById('pauseButton');
                pauseButton.style.display = 'none';
                let exitButton = document.getElementById('exitButton');
                exitButton.style.display = 'none';
            }
        }

        function keyPressed() {
            if (keyCode === ontouchstart) {
                if (!gameActive) {
                    resetGame();
                    gameActive = true;
                    startTime = int(millis() / 1000);
                }
                player.jump(); // Move the jump logic outside the 'gameActive' condition
            }
        }
        function touchStarted() {
            if (!gameActive) {
                resetGame();
                gameActive = true;
                startTime = int(millis() / 1000);
            }
            player.jump();
        }

        function mousePressed() {
            let pauseButton = document.getElementById('pauseButton');
            if (gameActive && mouseX >= 20 && mouseX <= 115 && mouseY >= 20 && mouseY <= 70) {
                if (!paused) {
                    paused = false;
                    console.log('Game Paused');
                    noLoop();
                    pauseButton.style.pointerEvents = 'none'; // I-disable ang pag-click sa button habang paused
                    pauseDialog.style.display = 'block';
                } else {
                    if (gameActive && touches[0].y >= player.y && touches[0].y <= player.y + player.image.height) {
                        player.jump();
                    }
                    return false; // I-override ang default na behavior ng touch input sa browser
                
                }
            }
        }
        
        


        function displayScore(pausedTime) {
            let current_time = int(millis() / 1000) - startTime - pausedTime;
            textSize(30);
            fill(64);
            textAlign(CENTER, CENTER);
            text(`Score: ${current_time}`, 180, 50);
            return current_time;
        }

        function resetGame() {
            gameActive = false;
            startTime = 0;
            score = 0;
            pausedTime = 0;
        }

        

        class Player {
            constructor() {
                this.x = 80;
                this.y = 300;
                this.gravity = 0;
                this.jumpSound = jumpSound;
                this.player_walk = [player_walk_1, player_walk_2];
                this.player_index = 0;
                this.player_jump = player_jump;
                this.image = this.player_walk[this.player_index];
                this.isJumping = false; // Add a variable to track jumping state
            }

            show() {
                image(this.image, this.x, this.y);
            }

            update() {
                this.applyGravity();
                this.animationState();
            }

            jump() {
                if (this.y >= 300) { // Allowing jump if the player is on or below the ground
                    this.gravity = -20;
                    this.jumpSound.play();
                    this.isJumping = true;
                }
            }

            applyGravity() {
                this.gravity += 1;
                this.y += this.gravity;
                if (this.y >= 355) {
                    this.y = 355;
                    this.gravity = 0; // Reset gravity upon landing
                    this.isJumping = false; // Reset jumping state when landed
                }
            }

            animationState() {
                if (this.isJumping) {
                    this.image = this.player_jump;
                } else {
                    this.player_index += 0.1;
                    if (this.player_index >= this.player_walk.length) {
                        this.player_index = 0;
                    }
                    this.image = this.player_walk[int(this.player_index)];
                }
            }

            collides(obstacle) {
                let playerRect = {
                    x: this.x,
                    y: this.y,
                    width: this.image.width,
                    height: this.image.height
                };

                let obstacleRect = {
                    x: obstacle.x,
                    y: obstacle.y,
                    width: obstacle.image.width,
                    height: obstacle.image.height
                };

                if (
                    playerRect.x < obstacleRect.x + obstacleRect.width &&
                    playerRect.x + playerRect.width > obstacleRect.x &&
                    playerRect.y < obstacleRect.y + obstacleRect.height &&
                    playerRect.y + playerRect.height > obstacleRect.y
                ) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        class Obstacle {
            constructor(type) {
                this.x = width;
                this.y = type === 'fly' ? 280 : 380; // Adjusted y-coordinate for snail obstacle
                this.frames = type === 'fly' ? [fly_1, fly_2] : [snail_1, snail_2];
                this.animation_index = 0;
                this.image = this.frames[this.animation_index];
            }

            show() {
                image(this.image, this.x, this.y);
            }

            update() {
                this.animationState();
                this.x -= 6;
            }

            offscreen() {
                return this.x < -100;
            }

            animationState() {
                this.animation_index += 0.1;
                if (this.animation_index >= this.frames.length) {
                    this.animation_index = 0;
                }
                this.image = this.frames[int(this.animation_index)];
            }
        }