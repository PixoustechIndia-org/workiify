/**
 * Workiify Animated Tab Favicon
 * Dynamically animates the Workiify chevron wings & apex logomark directly in the browser tab icon.
 */
(function() {
    if (typeof window === 'undefined' || typeof document === 'undefined') return;

    var canvas = document.createElement('canvas');
    canvas.width = 32;
    canvas.height = 32;
    var ctx = canvas.getContext('2d');
    if (!ctx) return;

    var link = document.querySelector("link[rel*='icon']");
    if (!link) {
        link = document.createElement('link');
        link.rel = 'icon';
        document.head.appendChild(link);
    }
    // From here on this script supplies the tab icon itself (a PNG frame ~12x a second). Any other
    // icon links (e.g. the "alternate icon" fallback) are removed: otherwise every frame makes the
    // browser re-evaluate them and re-request favicon.svg over and over. (Without JavaScript the
    // icons in the HTML are used as before.)
    Array.prototype.forEach.call(document.querySelectorAll("link[rel*='icon']"), function (other) {
        if (other !== link && other.parentNode) other.parentNode.removeChild(other);
    });
    link.rel = 'icon';
    link.type = 'image/png';

    var frame = 0;
    var isRunning = true;
    var animTimer = null;

    function drawFavicon(progress) {
        ctx.clearRect(0, 0, 32, 32);

        // 1. Dark Rounded Squircle Base
        var r = 7;
        ctx.fillStyle = '#0f172a';
        ctx.beginPath();
        ctx.moveTo(r, 0);
        ctx.lineTo(32 - r, 0);
        ctx.quadraticCurveTo(32, 0, 32, r);
        ctx.lineTo(32, 32 - r);
        ctx.quadraticCurveTo(32, 32, 32 - r, 32);
        ctx.lineTo(r, 32);
        ctx.quadraticCurveTo(0, 32, 0, 32 - r);
        ctx.lineTo(0, r);
        ctx.quadraticCurveTo(0, 0, r, 0);
        ctx.closePath();
        ctx.fill();

        // 2. Glowing Ring Pulse
        var pulse = (Math.sin(progress * Math.PI * 2) + 1) / 2; // 0 to 1
        ctx.strokeStyle = 'rgba(99, 102, 241, ' + (0.35 + pulse * 0.45) + ')';
        ctx.lineWidth = 1.2;
        ctx.stroke();

        ctx.save();
        ctx.translate(16, 16);
        // Subtle breathing scale
        var scale = 0.95 + pulse * 0.08;
        ctx.scale(scale, scale);
        ctx.translate(-16, -16);

        // 3. Left Wing (Slate / Indigo)
        var flapL = Math.sin(progress * Math.PI * 2) * 0.6;
        ctx.fillStyle = '#818cf8';
        // Bar 1
        ctx.beginPath();
        ctx.moveTo(7 + flapL, 9);
        ctx.lineTo(13.5, 12);
        ctx.lineTo(13.2, 13.5);
        ctx.lineTo(7 + flapL, 10.8);
        ctx.closePath();
        ctx.fill();
        // Bar 2
        ctx.beginPath();
        ctx.moveTo(8 + flapL, 13);
        ctx.lineTo(13, 15.5);
        ctx.lineTo(12.7, 17);
        ctx.lineTo(8 + flapL, 14.6);
        ctx.closePath();
        ctx.fill();
        // Bar 3
        ctx.beginPath();
        ctx.moveTo(9 + flapL, 17);
        ctx.lineTo(12, 18.8);
        ctx.lineTo(11.8, 20.2);
        ctx.lineTo(9 + flapL, 18.4);
        ctx.closePath();
        ctx.fill();

        // 4. Right Wing (Teal / Cyan)
        var flapR = -flapL;
        ctx.fillStyle = '#38bdf8';
        // Bar 1
        ctx.beginPath();
        ctx.moveTo(25 - flapR, 9);
        ctx.lineTo(18.5, 12);
        ctx.lineTo(18.8, 13.5);
        ctx.lineTo(25 - flapR, 10.8);
        ctx.closePath();
        ctx.fill();
        // Bar 2
        ctx.beginPath();
        ctx.moveTo(24 - flapR, 13);
        ctx.lineTo(19, 15.5);
        ctx.lineTo(19.3, 17);
        ctx.lineTo(24 - flapR, 14.6);
        ctx.closePath();
        ctx.fill();
        // Bar 3
        ctx.beginPath();
        ctx.moveTo(23 - flapR, 17);
        ctx.lineTo(20, 18.8);
        ctx.lineTo(20.2, 20.2);
        ctx.lineTo(23 - flapR, 18.4);
        ctx.closePath();
        ctx.fill();

        // 5. Center Apex "A / Chevron" (Pure White to Silver)
        ctx.fillStyle = '#ffffff';
        ctx.beginPath();
        ctx.moveTo(16, 6);
        ctx.lineTo(21.5, 23.5);
        ctx.lineTo(19, 23.5);
        ctx.lineTo(16, 13);
        ctx.lineTo(13, 23.5);
        ctx.lineTo(10.5, 23.5);
        ctx.closePath();
        ctx.fill();

        // 6. Amber Glow Dot in Center
        ctx.fillStyle = '#f59e0b';
        ctx.beginPath();
        ctx.arc(16, 20.2, 1.1 + pulse * 0.3, 0, Math.PI * 2);
        ctx.fill();

        // 7. Light Shine Sweep
        var shineX = ((progress * 1.8) % 1.8) * 36 - 12;
        var grad = ctx.createLinearGradient(shineX, 0, shineX + 8, 32);
        grad.addColorStop(0, 'rgba(255,255,255,0)');
        grad.addColorStop(0.5, 'rgba(255,255,255,0.4)');
        grad.addColorStop(1, 'rgba(255,255,255,0)');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, 32, 32);

        ctx.restore();

        link.href = canvas.toDataURL('image/png');
    }

    var totalFrames = 30; // 30 frames for ultra-smooth loop
    function tick() {
        if (!isRunning) return;
        frame = (frame + 1) % totalFrames;
        drawFavicon(frame / totalFrames);
    }

    // Run at ~12 frames per second (silky smooth in tab, negligible CPU usage)
    animTimer = setInterval(tick, 85);

    // Pause when user is not viewing tab to conserve CPU, resume on focus
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            isRunning = false;
        } else {
            isRunning = true;
            tick();
        }
    });
})();
