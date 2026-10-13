
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="game.css">
    <title>Snake Game</title>

</head>

<body>

    <main class="game-container">

        <h1 class="title">SNAKE GAME</h1>

        <div class="score-panel">
            <div class="score-box">
                <div class="score-label">SCORE</div>
                <div class="score-value" id="score">0</div>
            </div>

            <div class="score-box">
                <div class="score-label">BEST SCORE</div>
                <div class="score-value" id="best-score">0</div>
            </div>
        </div>

        <div class="game-board">
            <canvas id="game" width="600" height="400"
                    tabindex="0" aria-label="Snake game board"></canvas>
        </div>

        <div class="game-message" id="message" aria-live="polite">
            Press START GAME to play
        </div>

        <div class="controls">
            <button id="start-btn">START GAME</button>
            <button id="restart-btn">RESTART</button>
        </div>

        <div class="instructions">
            MOVE: ARROWS / WASD<br>
            PAUSE: SPACE
        </div>

        <a href="index.php" class="back-link">← BACK TO MENU</a>

    </main>

    <script>
        const canvas = document.getElementById("game");
        const ctx = canvas.getContext("2d");

        const scoreElement = document.getElementById("score");
        const bestElement = document.getElementById("best-score");
        const messageElement = document.getElementById("message");
        const startButton = document.getElementById("start-btn");
        const restartButton = document.getElementById("restart-btn");

        const CELL = 20;
        const COLS = canvas.width / CELL;
        const ROWS = canvas.height / CELL;

        let snake;
        let berry;
        let direction;
        let nextDirection;
        let score = 0;
        let bestScore = loadBestScore();
        let gameState = "ready";
        let gameLoop = null;

        bestElement.textContent = bestScore;

        function loadBestScore() {
            try {
                return Number(localStorage.getItem("snakeBestScore")) || 0;
            } catch {
                return 0;
            }
        }

        function saveBestScore() {
            try {
                localStorage.setItem("snakeBestScore", bestScore);
            } catch {
                // Gra działa również bez zapisu rekordu.
            }
        }

        function resetGame() {
            clearInterval(gameLoop);
            gameLoop = null;

            snake = [
                { x: 8, y: 10 },
                { x: 7, y: 10 },
                { x: 6, y: 10 },
                { x: 5, y: 10 }
            ];

            direction = { x: 1, y: 0 };
            nextDirection = { x: 1, y: 0 };

            score = 0;
            scoreElement.textContent = score;

            spawnBerry();

            gameState = "ready";
            startButton.textContent = "START GAME";
            messageElement.textContent = "Press START GAME to play";

            draw();
        }

        function spawnBerry() {
            do {
                berry = {
                    x: Math.floor(Math.random() * COLS),
                    y: Math.floor(Math.random() * ROWS)
                };
            } while (
                snake &&
                snake.some(part =>
                    part.x === berry.x && part.y === berry.y
                )
            );
        }

        function startGame() {
            if (gameState === "running") return;

            if (gameState === "over") {
                resetGame();
            }

            gameState = "running";
            startButton.textContent = "PAUSE";
            messageElement.textContent = "Good luck!";

            canvas.focus();
            clearInterval(gameLoop);
            gameLoop = setInterval(update, 120);
        }

        function pauseGame() {
            if (gameState !== "running") return;

            clearInterval(gameLoop);
            gameLoop = null;
            gameState = "paused";

            startButton.textContent = "CONTINUE";
            messageElement.textContent = "Game paused";

            draw();
        }

        function endGame() {
            clearInterval(gameLoop);
            gameLoop = null;
            gameState = "over";

            startButton.textContent = "PLAY AGAIN";
            messageElement.textContent = "GAME OVER! Final score: " + score;

            draw();
        }

        function update() {
            direction = { ...nextDirection };

            const head = {
                x: snake[0].x + direction.x,
                y: snake[0].y + direction.y
            };

            const eating = head.x === berry.x && head.y === berry.y;

            // Check wall collision.
            if (
                head.x < 0 || head.x >= COLS ||
                head.y < 0 || head.y >= ROWS
            ) {
                endGame();
                return;
            }

            // When not eating, the tail moves away this turn.
            const bodyToCheck = eating ? snake : snake.slice(0, -1);

            const hitBody = bodyToCheck.some(part =>
                part.x === head.x && part.y === head.y
            );

            if (hitBody) {
                endGame();
                return;
            }

            snake.unshift(head);

            if (eating) {
                score += 10;
                scoreElement.textContent = score;

                if (score > bestScore) {
                    bestScore = score;
                    bestElement.textContent = bestScore;
                    saveBestScore();
                }

                if (snake.length === COLS * ROWS) {
                    clearInterval(gameLoop);
                    gameLoop = null;
                    gameState = "over";
                    startButton.textContent = "PLAY AGAIN";
                    messageElement.textContent = "YOU WIN! Perfect score!";
                    draw();
                    return;
                }

                spawnBerry();
            } else {
                snake.pop();
            }

            draw();
        }

        function drawGrid() {
            ctx.strokeStyle = "rgba(0, 225, 255, 0.09)";
            ctx.lineWidth = 1;

            for (let x = 0; x <= canvas.width; x += CELL) {
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x, canvas.height);
                ctx.stroke();
            }

            for (let y = 0; y <= canvas.height; y += CELL) {
                ctx.beginPath();
                ctx.moveTo(0, y);
                ctx.lineTo(canvas.width, y);
                ctx.stroke();
            }
        }

        function drawBerry() {
            const x = berry.x * CELL + CELL / 2;
            const y = berry.y * CELL + CELL / 2;

            // Glow.
            ctx.save();
            ctx.shadowColor = "#ff3158";
            ctx.shadowBlur = 15;

            // Berry body.
            ctx.fillStyle = "#ff3158";
            ctx.beginPath();
            ctx.arc(x, y + 2, 7, 0, Math.PI * 2);
            ctx.fill();

            ctx.restore();

            // Small green leaf.
            ctx.fillStyle = "#45ff9a";
            ctx.beginPath();
            ctx.ellipse(x + 3, y - 6, 4, 2, -0.6, 0, Math.PI * 2);
            ctx.fill();

            // Highlight.
            ctx.fillStyle = "#ffffff";
            ctx.beginPath();
            ctx.arc(x - 2, y, 1.5, 0, Math.PI * 2);
            ctx.fill();
        }

        function drawSnake() {
            snake.forEach((part, index) => {
                const padding = 2;
                const x = part.x * CELL + padding;
                const y = part.y * CELL + padding;
                const size = CELL - padding * 2;

                ctx.save();

                ctx.shadowColor = index === 0 ? "#00ffb3" : "#00e5ff";
                ctx.shadowBlur = index === 0 ? 15 : 6;

                if (index === 0) {
                    ctx.fillStyle = "#8affd5";
                } else {
                    const alpha = Math.max(0.45, 1 - index * 0.025);
                    ctx.fillStyle = `rgba(0, 229, 255, ${alpha})`;
                }

                ctx.beginPath();
                ctx.roundRect(x, y, size, size, 5);
                ctx.fill();

                ctx.restore();

                // Eyes on the head.
                if (index === 0) {
                    ctx.fillStyle = "#07111a";

                    let eye1;
                    let eye2;

                    if (direction.x === 1) {
                        eye1 = { x: x + 13, y: y + 4 };
                        eye2 = { x: x + 13, y: y + 11 };
                    } else if (direction.x === -1) {
                        eye1 = { x: x + 3, y: y + 4 };
                        eye2 = { x: x + 3, y: y + 11 };
                    } else if (direction.y === -1) {
                        eye1 = { x: x + 4, y: y + 3 };
                        eye2 = { x: x + 11, y: y + 3 };
                    } else {
                        eye1 = { x: x + 4, y: y + 13 };
                        eye2 = { x: x + 11, y: y + 13 };
                    }

                    [eye1, eye2].forEach(eye => {
                        ctx.beginPath();
                        ctx.arc(eye.x, eye.y, 1.7, 0, Math.PI * 2);
                        ctx.fill();
                    });
                }
            });
        }

        function drawOverlay(title, subtitle) {
            ctx.fillStyle = "rgba(4, 9, 18, 0.78)";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.textAlign = "center";
            ctx.shadowColor = "#00e5ff";
            ctx.shadowBlur = 15;
            ctx.fillStyle = "#00e5ff";
            ctx.font = "bold 36px Arial";
            ctx.fillText(title, canvas.width / 2, canvas.height / 2 - 12);

            ctx.shadowBlur = 0;
            ctx.fillStyle = "#d4eaf2";
            ctx.font = "16px Arial";
            ctx.fillText(subtitle, canvas.width / 2, canvas.height / 2 + 25);
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.fillStyle = "#080e19";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            drawGrid();
            drawBerry();
            drawSnake();

            if (gameState === "ready") {
                drawOverlay("SNAKE GAME", "Press START GAME");
            } else if (gameState === "paused") {
                drawOverlay("PAUSED", "Press CONTINUE");
            } else if (gameState === "over") {
                drawOverlay("GAME OVER", "Score: " + score);
            }
        }

        function changeDirection(x, y) {
            if (
                x === -direction.x &&
                y === -direction.y
            ) {
                return;
            }

            nextDirection = { x, y };
        }

        document.addEventListener("keydown", event => {
            const key = event.key.toLowerCase();

            const gameKeys = [
                "arrowup", "arrowdown",
                "arrowleft", "arrowright",
                "w", "a", "s", "d", " "
            ];

            if (gameKeys.includes(key)) {
                event.preventDefault();
            }

            if (key === " " && gameState === "running") {
                pauseGame();
                return;
            }

            if (
                (key === " " || key === "enter") &&
                (gameState === "ready" || gameState === "paused")
            ) {
                startGame();
                return;
            }

            if (key === "arrowup" || key === "w") {
                changeDirection(0, -1);
            } else if (key === "arrowdown" || key === "s") {
                changeDirection(0, 1);
            } else if (key === "arrowleft" || key === "a") {
                changeDirection(-1, 0);
            } else if (key === "arrowright" || key === "d") {
                changeDirection(1, 0);
            }
        });

        startButton.addEventListener("click", () => {
            if (gameState === "running") {
                pauseGame();
            } else {
                startGame();
            }
        });

        restartButton.addEventListener("click", () => {
            resetGame();
            startGame();
        });

        resetGame();
    </script>

</body>
</html>
