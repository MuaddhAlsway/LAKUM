<?php 
require_once 'config/database.php';

$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id <= 0) {
    header('Location: index.php');
    exit();
}

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    header('Location: index.php');
    exit();
}

// Get gallery images
$images = $conn->query("SELECT image_path FROM event_images WHERE event_id = $event_id");
$gallery = [];
while ($img = $images->fetch_assoc()) {
    $gallery[] = $img['image_path'];
}

$conn->close();

$event_date = date('d M', strtotime($event['event_date']));
$event_year = date('Y', strtotime($event['event_date']));
$date_range = $event['event_time'] ?: $event_date;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> - LAKUM</title>
    <link rel="stylesheet" href="Home.css">
    <link rel="icon" href="assest/logo-lakum- (1).png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>
    <div class="headerContainer">
        <i class="ri-menu-line"></i>
        <i class="ri-close-line"></i>
        <div class="logo">
            <img src="assest/logo-lakum- (1).png" alt="">
        </div>
        <div class="MenuHeader">
            <ul>
                <li><a href="index.php">HOME</a></li>
                <li><a href="AboutUs.php">ABOUT US</a></li>
                <li><a href="Space.html">LAKUM SPACES</a></li>
                <li><a href="exhibitions.php">EXHIBITIONS</a></li>
                <li><a href="HomeLukum.php#upcoming">EVENTS & WORKSHOPS</a></li>
                <li><a href="Calender.php">CALENDER</a></li>
                <li><a href="Space.html#form">Shop</a></li>      
            </ul>
        </div>
    </div>

    <div class="ahmedmatter" style="background-image: url('<?php echo $event['cover_image'] ?: 'assest/img-3.JPG'; ?>');">
        <div class="dateAhmedmaater">
            <p><?php echo $date_range; ?></p>
        </div>
        <div class="nameAhmedmatter">
            <p><?php echo htmlspecialchars($event['title']); ?></p>
        </div>
        <div class="yearahmedmatter">
            <p><?php echo $event_year; ?></p>
        </div>
    </div>

    <div class="ahmedHiglight">
        <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
    </div>

    <?php if (count($gallery) > 0): ?>
    <div class="ContainerSlide">
        <div class="slider-container">
            <div class="EXHIBITIONPREVIUSCONTAINER">
                <h4>EVENT GALLERY</h4>
            </div>
            <button class="nav prev">&#10094;</button>
            <div class="slider-track">
                <?php foreach ($gallery as $image): ?>
                <div class="card"><img src="<?php echo htmlspecialchars($image); ?>" alt="Gallery"></div>
                <?php endforeach; ?>
            </div>
            <button class="nav next">&#10095;</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="HiglihtAhmedMatter">
        <h4>Create Your own Exhibition <span>with Lakum</span></h4>
        <button><a href="Space.html#form">Booking LAKUM</a></button>
    </div>

    <footer class="FOOTER1">
        <div class="footer-container">
            <div class="footer-logo">
                <img src="assest/logo-lakum- (1).png" alt="">
            </div>
            <div class="MenuFooter">
                <ul>
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="AboutUs.php">ABOUT US</a></li>
                    <li><a href="Space.php">LAKUM SPACES</a></li>
                    <li><a href="HomeLukum.php">EXHIBITIONS</a></li>
                    <li><a href="HomeLukum.php#upcoming">EVENTS & WORKSHOPS</a></li>
                    <li><a href="Calender.php">CALENDER</a></li>
                    <li><a href="Space.php#form">SHOP</a></li>    
                </ul>
            </div>
            <div class="social-icons">
                <a href="#"><i class="ri-facebook-fill"></i></a>
                <a href="#"><i class="ri-instagram-fill"></i></a>
                <a href="#"><i class="ri-twitter-x-fill"></i></a>
                <a href="#"><i class="ri-linkedin-fill"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 LAKUM. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const menuIcon = document.querySelector('.ri-menu-line');
        const closeIcon = document.querySelector('.ri-close-line');
        const menu = document.querySelector('.MenuHeader ul');

        menuIcon.addEventListener('click', () => {
            menu.classList.add('active');
            menuIcon.style.display = 'none';
            closeIcon.style.display = 'block';
        });

        closeIcon.addEventListener('click', () => {
            menu.classList.remove('active');
            menuIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        });

        const track = document.querySelector('.slider-track');
        const prevBtn = document.querySelector('.nav.prev');
        const nextBtn = document.querySelector('.nav.next');
        const cards = document.querySelectorAll('.card');

        if (track && cards.length > 0) {
            let isDragging = false;
            let startX = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let animationID = 0;
            const cardWidth = cards[0].offsetWidth + 20;

            track.addEventListener('mousedown', dragStart);
            track.addEventListener('mousemove', dragMove);
            track.addEventListener('mouseup', dragEnd);
            track.addEventListener('mouseleave', dragEnd);
            track.addEventListener('touchstart', dragStart);
            track.addEventListener('touchmove', dragMove);
            track.addEventListener('touchend', dragEnd);

            function dragStart(e) {
                isDragging = true;
                startX = getX(e);
                animationID = requestAnimationFrame(animation);
                track.style.transition = 'none';
            }

            function dragMove(e) {
                if (!isDragging) return;
                const currentX = getX(e);
                const diff = currentX - startX;
                currentTranslate = prevTranslate + diff;
            }

            function dragEnd() {
                cancelAnimationFrame(animationID);
                isDragging = false;
                const movedBy = currentTranslate - prevTranslate;
                if (movedBy < -50) {
                    currentTranslate = prevTranslate - cardWidth;
                } else if (movedBy > 50) {
                    currentTranslate = prevTranslate + cardWidth;
                } else {
                    currentTranslate = prevTranslate;
                }
                const maxTranslate = 0;
                const minTranslate = -(track.scrollWidth - track.parentElement.offsetWidth);
                if (currentTranslate > maxTranslate) currentTranslate = maxTranslate;
                if (currentTranslate < minTranslate) currentTranslate = minTranslate;
                prevTranslate = currentTranslate;
                track.style.transition = 'transform 0.4s ease';
                track.style.transform = `translateX(${currentTranslate}px)`;
            }

            function animation() {
                setSliderPosition();
                if (isDragging) requestAnimationFrame(animation);
            }

            function setSliderPosition() {
                track.style.transform = `translateX(${currentTranslate}px)`;
            }

            function getX(e) {
                return e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    currentTranslate -= cardWidth * 3;
                    dragEnd();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    currentTranslate += cardWidth * 3;
                    dragEnd();
                });
            }
        }
    </script>
</body>
</html>
