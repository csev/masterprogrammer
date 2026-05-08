<?php
$targetUrl = 'https://online.dr-chuck.com/about.php';
$advanceMs = 3000;
$slides = [
    ['src' => 'master-image-01-carpenter.png', 'alt' => 'Carpenter'],
    ['src' => 'master-image-02-trades.png', 'alt' => 'Trades'],
    ['src' => 'master-image-03-abstract.png', 'alt' => 'Abstract'],
];
$slideCount = count($slides);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Programmer</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html {
            height: 100%;
            min-height: 100%;
            min-height: 100dvh;
        }
        body {
            min-height: 100%;
            min-height: 100dvh;
            height: 100%;
            background: #000;
            overflow: hidden;
        }
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            clip-path: inset(50%);
            white-space: nowrap;
            border: 0;
        }
        .stage-link {
            --stage-pad-top: env(safe-area-inset-top, 0px);
            --stage-pad-right: env(safe-area-inset-right, 0px);
            --stage-pad-bottom: env(safe-area-inset-bottom, 0px);
            --stage-pad-left: env(safe-area-inset-left, 0px);
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--stage-pad-top) var(--stage-pad-right) var(--stage-pad-bottom) var(--stage-pad-left);
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        .stage-link:focus {
            outline: none;
        }
        .stage-link:focus-visible {
            outline: 3px solid #fff;
            outline-offset: -6px;
        }
        .carousel {
            position: relative;
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            /* Flex child of .stage-link: shrink to available space and center the img stack */
            flex: 1 1 auto;
            min-height: 0;
            min-width: 0;
        }
        .slide {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            object-position: center;
            opacity: 0;
            transition: opacity 0.6s ease;
            pointer-events: none;
        }
        .slide.active {
            opacity: 1;
        }
        @media (prefers-reduced-motion: reduce) {
            .slide {
                transition: none;
            }
        }
    </style>
</head>
<body>
    <p id="slide-status" class="visually-hidden" aria-live="polite" aria-atomic="true"></p>
    <a
        class="stage-link"
        id="stage"
        href="<?= htmlspecialchars($targetUrl, ENT_QUOTES, 'UTF-8') ?>"
        aria-label="Continue to online.dr-chuck.com/about.php. Full screen control: activates this link."
    >
        <div
            class="carousel"
            role="region"
            aria-roledescription="carousel"
            aria-label="Introduction slideshow, <?= (int) $slideCount ?> slides"
        >
            <?php foreach ($slides as $i => $s): ?>
                <?php $active = $i === 0; ?>
                <img
                    class="slide<?= $active ? ' active' : '' ?>"
                    src="<?= htmlspecialchars($s['src'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($s['alt'], ENT_QUOTES, 'UTF-8') ?>"
                    <?php if (!$active): ?>inert aria-hidden="true"<?php endif; ?>
                >
            <?php endforeach; ?>
        </div>
    </a>
    <script>
        (function () {
            var targetUrl = <?= json_encode($targetUrl, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            var advanceMs = <?= (int) $advanceMs ?>;
            var stage = document.getElementById('stage');
            var slides = document.querySelectorAll('.slide');
            var statusEl = document.getElementById('slide-status');
            var n = slides.length;
            var i = 0;

            function announceSlide(index) {
                if (!statusEl || !slides[index]) return;
                var label = slides[index].getAttribute('alt') || ('Slide ' + (index + 1));
                statusEl.textContent = 'Slide ' + (index + 1) + ' of ' + n + ': ' + label;
            }

            function go() {
                window.location.href = targetUrl;
            }

            function show(next) {
                slides[i].classList.remove('active');
                slides[i].setAttribute('inert', '');
                slides[i].setAttribute('aria-hidden', 'true');

                i = next;

                slides[i].classList.add('active');
                slides[i].removeAttribute('inert');
                slides[i].removeAttribute('aria-hidden');
                announceSlide(i);
            }

            function tick() {
                if (i >= n - 1) {
                    go();
                    return;
                }
                show(i + 1);
            }

            announceSlide(0);

            stage.addEventListener('keydown', function (e) {
                if (e.key === ' ' || e.key === 'Spacebar') {
                    e.preventDefault();
                    go();
                }
            });

            setInterval(tick, advanceMs);
        })();
    </script>
</body>
</html>
