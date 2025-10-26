<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAKUM</title>
    <link rel="stylesheet" href="Home.css">
    <link rel="stylesheet" href="me.css">
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

    <div class="HomeContainer">
        <img src="assest/img-4.png" alt="">
    </div>

    <div class="aboutUs">
        <div class="textAboutUs">
            <div class="titleAboutUs">
                <h4>ABOUT US</h4>
            </div>
            <div class="paragraphAboutUs">
                <p>
                    Lakum Artspace is a multi-functional contemporary gallery, educational hub, 
                    library, shop and cafe located in the heart of Riyadh. Dedicated to nurturing
                    emerging and established contemporary artists and designers from Saudi Arabia,
                    the region and across the globe. Lakum Artspace's program offers a platform for
                    curated exhibitions and a non-profit educational forum for artists, creative
                    practitioners and art enthusiasts of all ages.
                </p>
                <p>
                    Lakum Artspace is a multi-functional contemporary gallery, educational hub,
                    library, shop and cafe located in the heart of Riyadh. Dedicated to nurturing
                    emerging and established contemporary artists and designers from Saudi Arabia,
                    the region and across the globe. Lakum Artspace's program offers a platform for
                    curated exhibitions and a non-profit educational forum for artists, creative
                    practitioners and art enthusiasts of all ages.
                </p>
            </div>
        </div>
        <div class="imgAboutUs" id="imgAboutUsSection">
            <img src="assest/img-3.JPG" alt="" id="imgAboutUsImage">
        </div>
    </div>

    <div class="banner">
        <div class="bannerImage" id="bannerImageSection">
            <img src="assest/img-3.JPG" alt="Lakum Artspace" id="bannerImageImg">
        </div>
        <div class="bannerText">
            <h2>WORKSHOPS & SEMINARS</h2>
            <p>
                A dedicated workshop and seminar area offers a public program of talks, panel discussions,
                screenings, and short courses. Lakum Artspace stages debates in contemporary art and design,
                hosting a mixture of local, regional, and global guest speakers. The space also hosts workshops
                and activities for young people and children, providing a stimulating environment for learning
                and exploration.
            </p>
        </div>
    </div>

    <div class="numberCount">
        <div class="Exhibition">
            <a href="">+39</a>
            <p>Exhibitions <br> &Workshops</p>
        </div>
        <div class="ArtPieces">
            <a href="">+200K</a>
            <p>Art <br> Pieces</p>
        </div>
        <div class="Artisinstructor">
            <a href="">+300K</a>
            <p>Artist & Instructor</p>
        </div>
        <div class="vistor">
            <a href="">+55K</a>
            <p>Participants & <br> Visitors</p>
        </div>
    </div>

    <div class="bannnerArtPaceGallary">
        <div class="hilightBanner">
            <p class="Higlight">Artspace, Gallary,Hub, Library, Shop,Cafe</p>
            <p>A home for every piece of art and culture</p>
        </div>
    </div>

    <div class="ForOurClient">
        <div class="titleForOurClient">
            <h4>OUR CLIENTS</h4>
        </div>
        <div class="infiniteSlider">
            <div class="sliderTrack" id="sliderTrack">
                <div class="slide"><img src="Logo/eyewa.png" alt=""></div>
                <div class="slide"><img src="Logo/SHEIN.png" alt=""></div>
                <div class="slide"><img src="Logo/Namshi.png" alt=""></div>
                <div class="slide"><img src="Logo/بنك التنمية الإجتماعية.png" alt=""></div>
                <div class="slide"><img src="Logo/huda-beauty.webp" alt=""></div>
                <div class="slide"><img src="Logo/Sephora-Logo.png" alt=""></div>
            </div>
        </div>
    </div>

    <div class="ContainLakum">
        <div class="overlay" id="upcomingEventsContainer">
            <!-- Events will be loaded here dynamically -->
        </div>
    </div>

    <div class="buttonDiscoverMore">
        <button onclick="window.location.href='HomeLukum.php'">Discover More</button>
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

    <script src="Home.js"></script>
    <script>
        // Load dynamic images from latest events
        fetch('api/get_events.php?type=latest&limit=2')
            .then(response => response.json())
            .then(events => {
                if (events.length > 0) {
                    // Update imgAboutUs with first event image
                    const imgAboutUs = document.getElementById('imgAboutUsImage');
                    if (imgAboutUs && events[0].cover_image) {
                        imgAboutUs.src = events[0].cover_image;
                        console.log('imgAboutUs: Updated with', events[0].cover_image);
                    }
                    
                    // Update bannerImage with second event image (or first if only one exists)
                    const bannerImg = document.getElementById('bannerImageImg');
                    if (bannerImg) {
                        const bannerEvent = events.length > 1 ? events[1] : events[0];
                        if (bannerEvent.cover_image) {
                            bannerImg.src = bannerEvent.cover_image;
                            console.log('bannerImage: Updated with', bannerEvent.cover_image);
                        }
                    }
                }
            })
            .catch(error => console.error('Error loading images:', error));

        // Load upcoming events for homepage (up to 3, but only as many as exist)
        fetch('api/get_events.php?type=closest&limit=3')
            .then(response => response.json())
            .then(events => {
                const container = document.getElementById('upcomingEventsContainer');
                container.innerHTML = '';
                
                if (events.length === 0) {
                    container.innerHTML = '<p style="text-align: center; padding: 40px; color: #666; grid-column: 1 / -1;">No upcoming events yet.</p>';
                    return;
                }
                
                events.forEach(event => {
                    const eventCard = `
                        <div class="overlay1" onclick="window.location.href='event-detail.php?id=${event.id}'" style="cursor: pointer;">
                            <div class="imagCover">
                                <img src="${event.cover_image || 'assest/img-3.JPG'}" alt="${event.title}">
                            </div>
                            <div class="event-details">
                                <div class="details-row">
                                    <div class="date">
                                        <h4>${event.day} ${event.month_short.toUpperCase()}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += eventCard;
                });
            })
            .catch(error => console.error('Error loading events:', error));
    </script>
</body>
</html>
