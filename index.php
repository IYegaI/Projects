<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
<link rel="stylesheet" href="snake.css">
<link rel="stylesheet" href="background.css">
    <title>FireSnake game</title>
</head>
<body>
    <div class="snake-decoration">
    <svg viewBox="0 0 1000 700" preserveAspectRatio="none">
        <path id="snakePath"
            d="M -50 230
            H 680
            Q 730 230 730 280
            V 850
            Q 730 900 780 900
            H 1100
            V 100
            Q 1100 50 1050 50
            H 250
            Q 200 50 200 100
            V 650
            Q 200 700 150 700
            H -100"
        fill="none"/>

        <g class="snake">
            <circle r="8">
                <animateMotion dur="16s" repeatCount="indefinite">
                    <mpath href="#snakePath"/>
                </animateMotion>
            </circle>

            <circle r="8">
                <animateMotion dur="16s" begin="-0.15s" repeatCount="indefinite">
                    <mpath href="#snakePath"/>
                </animateMotion>
            </circle>

            <circle r="8">
                <animateMotion dur="16s" begin="-0.30s" repeatCount="indefinite">
                    <mpath href="#snakePath"/>
                </animateMotion>
            </circle>

            <circle r="8">
                <animateMotion dur="16s" begin="-0.45s" repeatCount="indefinite">
                    <mpath href="#snakePath"/>
                </animateMotion>
            </circle>

            <circle r="8">
                <animateMotion dur="16s" begin="-0.60s" repeatCount="indefinite">
                    <mpath href="#snakePath"/>
                </animateMotion>
            </circle>

            <circle r="8">
                <animateMotion dur="16s" begin="-0.75s" repeatCount="indefinite">
                    <mpath href="#snakePath"/>
                </animateMotion>
            </circle>

            <circle r="8">
                <animateMotion dur="16s" begin="-0.90s" repeatCount="indefinite">
                    <mpath href="#snakePath"/>
                </animateMotion>
            </circle>

            <circle r="8">
                <animateMotion dur="16s" begin="-1.05s" repeatCount="indefinite">
                    <mpath href="#snakePath"/>
                </animateMotion>
            </circle>
        </g>
    </svg>
</div>
    <div id="container">
        <div class="grid-background">
            <div class="grid-lines"></div>
        </div>
        <div id="profilWindow">
            <img src="R.jpg" alt="">
            <p id="nickname">Nickname</p>
        </div>
        <div id="gameInterface">
            <div id="gameName">
                <h1 id="logoText">Fire Snake</h1>
            </div>
            <div id="gameMenu">
                <p><a href="game.php" id="playButton">PLAY</a></p>
                <p><a href="#" id="settingButton">SETTING</a></p>
                <p><a href="#" id="logInButton">Log In to save <br> your progress</a></p>
            </div>
        </div>
    </div>    
        <div id="bottom">
            <footer>
                <p>&copy;Created by IYegaI</p>
            </footer>
        </div>    
</body>
</html>