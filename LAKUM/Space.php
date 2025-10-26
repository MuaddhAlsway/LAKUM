<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAKUM Spaces</title>
    <link rel="icon" href="assest/logo-lakum- (1).png" type="image/png">
    <link rel="stylesheet" href="Home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <li><a href="Space.php">LAKUM SPACES</a></li>
                <li><a href="HomeLukum.php">EXHIBITIONS</a></li>
                <li><a href="HomeLukum.php#upcoming">EVENTS & WORKSHOPS</a></li>
                <li><a href="Calender.php">CALENDER</a></li>
                <li><a href="Space.php#form">SHOP</a></li>    
            </ul>
        </div>
    </div>

    <div class="Space" id="spaceHeroSection">
        <div class="nameSpace">
           <p>Discover Our Dynamic <span>Venus Space</span></p>
        </div>
        <div class="CollectionSpace">
            <p>Art</p>
            <p>Gallary</p>
            <p>Hub</p>
            <p>Library</p>
            <p>Shop</p>
            <p>Cafe</p>
        </div>
    </div>

    <div class="SpaceVENUMContainer">
        <div class="titleSpaceVenum">
            <h4>LAKUM VENUE</h4>
        </div>

        <div class="SpaceVENUMContent">
            <div class="LeftText">
                <p>
                    Lakum is more than just a space — it's a multi-dimensional destination for art, 
                    culture, and connection. Our diverse facilities are designed to inspire, engage, 
                    and bring communities together
                </p>
                <p>
                    Lakum Artspace offers unique exhibitions and educational experiences 
                    showcasing established and emerging artists and designers from Saudi Arabia, 
                    the MENA region and internationally. Lakum Artspace is committed to working 
                    collaboratively with regional and international galleries, museums and publications 
                    to produce innovative projects
                </p>
            </div>

            <div class="imgRightSpaceVenum">
                <img src="./HADAF Company/IMG_3946 2.png" alt="">
            </div>
        </div>
        
        <div class="ContainerSlide1">
            <div class="slider-container1">
                <button class="nav prev">&#10094;</button>
                <div class="slider-track">
                    <div class="card"><img src="HADAF Company/IMG_3941.png" alt="1"></div>
                    <div class="card"><img src="HADAF Company/IMG_3970.JPG" alt="2"></div>
                    <div class="card"><img src="HADAF Company/IMG_3942.png" alt="3"></div>
                    <div class="card"><img src="HADAF Company/IMG_3943.png" alt="4"></div>
                    <div class="card"><img src="HADAF Company/IMG_3944 2.png" alt="5"></div>
                    <div class="card"><img src="HADAF Company/IMG_3947 2.png" alt="6"></div>
                    <div class="card"><img src="HADAF Company/IMG_3949 2.png" alt="7"></div>
                </div>
                <button class="nav next">&#10095;</button>
            </div>
        </div>
    </div>

    <section id="generic_price_table">
        <div class="price-heading">
            <h1>Lakum Hall Pricing</h1>
        </div>

        <div class="flex-container">
            <div class="top-row">
                <div class="generic_content">
                    <div class="generic_head_price">
                        <div class="generic_head_content"><div class="head_bg"></div><div class="head"><span>Lakum Hall 1</span></div></div>
                        <div class="generic_price_tag"><span class="price">12,000 <img src="saudi arabia/currency.png" alt="SAR" class="currency-icon"></span></div>
                    </div>
                    <div class="generic_feature_list">
                        <ul>
                            <li>Support Services – logistical and technical support.</li>
                            <li>Custom Exhibition Stands – lighting, sound, AC, power.</li>
                        </ul>
                    </div>
                    <div class="generic_price_btn"><a href="Space.php#form">Book Now</a></div>
                </div>

                <div class="generic_content">
                    <div class="generic_head_price">
                        <div class="generic_head_content"><div class="head_bg"></div><div class="head"><span>Lakum Hall 2</span></div></div>
                        <div class="generic_price_tag"><span class="price">7,500<img src="saudi arabia/currency.png" alt="SAR" class="currency-icon"></span></div>
                    </div>
                    <div class="generic_feature_list">
                        <ul>
                            <li>Support Services – logistical and technical support.</li>
                            <li>Operational Service – lighting, sound, AC, power.</li>
                        </ul>
                    </div>
                    <div class="generic_price_btn"><a href="Space.php#form">Book Now</a></div>
                </div>

                <div class="generic_content">
                    <div class="generic_head_price">
                        <div class="generic_head_content"><div class="head_bg"></div><div class="head"><span>Lakum Hall 3</span></div></div>
                        <div class="generic_price_tag"><span class="price">4000<img src="saudi arabia/currency.png" alt="SAR" class="currency-icon"></span></div>
                    </div>
                    <div class="generic_feature_list">
                        <ul>
                            <li>Support Services – logistical and technical support.</li>
                            <li>Operational Service – lighting, sound, AC, power.</li>
                        </ul>
                    </div>
                    <div class="generic_price_btn"><a href="Space.php#form">Book Now</a></div>
                </div>
            </div>

            <div class="bottom-row">
                <div class="generic_content">
                    <div class="generic_head_price">
                        <div class="generic_head_content"><div class="head_bg"></div><div class="head"><span>Meeting Room</span></div></div>
                        <div class="generic_price_tag"><span class="price">2500<img src="saudi arabia/currency.png" alt="SAR" class="currency-icon"></span></div>
                    </div>
                    <div class="generic_feature_list"><ul><li>Empty</li></ul></div>
                    <div class="generic_price_btn"><a href="Space.php#form">Get More Info</a></div>
                </div>

                <div class="generic_content">
                    <div class="generic_head_price">
                        <div class="generic_head_content"><div class="head_bg"></div><div class="head"><span>Cafe Costs</span></div></div>
                        <div class="generic_price_tag"><span class="price">3500<img src="saudi arabia/currency.png" alt="SAR" class="currency-icon"></span></div>
                    </div>
                    <div class="generic_feature_list"><ul><li>*Benefit from the same amount with orders</li></ul></div>
                    <div class="generic_price_btn"><a href="Space.php#form">Get More Info</a></div>
                </div>
            </div>
        </div>
    </section>

    <section id="form">
        <form action="" method="POST" class="contact-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">Full Name</label>
                    <div class="input-icon">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="firstName" name="firstName" placeholder="John" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastName">Company Name</label>
                    <div class="input-icon">
                        <i class="fa-solid fa-building"></i>
                        <input type="text" id="lastName" name="lastName" placeholder="Doe Ltd" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-icon">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" id="phone" name="phone" placeholder="+966 5XXXXXXXX" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-icon">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="_replyto" placeholder="example@gmail.com" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <div class="input-icon">
                    <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>
                </div>
            </div>

            <button class="ButtonFormRow" type="submit">Submit</button>
        </form>
    </section>  

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
        // Menu toggle
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

        // Load most recent event for Space hero background
        fetch('api/get_events.php?type=latest&limit=1')
            .then(response => response.json())
            .then(events => {
                if (events.length > 0 && events[0].cover_image) {
                    const spaceSection = document.getElementById('spaceHeroSection');
                    spaceSection.style.backgroundImage = `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('${events[0].cover_image}')`;
                }
            })
            .catch(error => console.error('Error loading space background:', error));

        // Slider functionality
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
