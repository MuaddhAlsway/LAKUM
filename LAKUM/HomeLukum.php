<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAKUM</title>
    <link rel="icon" href="assest/logo-lakum- (1).png" type="image/png">
    <link rel="stylesheet" href="Home.css">
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

    <div class="LukumContiner">
        <div class="title">
            <h4>UPCOMING</h4>
        </div>
        <div class="pastExhibitLukum" id="upcomingPreview">
            <!-- 3 upcoming events will be loaded here -->
        </div>
    </div>

    <div class="upcomingEventLAKUM" id="upcoming">
        <div class="ComingEventContainerLAKUM">
            <div class="imgHeaderLakum" id="featuredEvent">
                <!-- Most recent event will be loaded here -->
            </div>
        </div>
    </div>

    <div class="previousEventContainer">
        <div class="titlePreviousEventWrapper">
            <h4>PREVIOUS EXHIBITION</h4>
        </div>
        <div class="ColumnContainer" id="previousEvents">
            <!-- Past events will be loaded here -->
        </div>
    </div>

    <div class="ExploreCoverLakum">
        <div class="CoverImage" id="exploreCoverSection">
            <div class="CoverImageLakum">
                <h4>Exclusive Paid Workshops</h4>
            </div>
            <button class="ExploreMoreButton" onclick="window.location.href='Calender.php'">
                Explore More
            </button>
        </div>
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
        // Load upcoming events preview (up to 3, but only as many as exist)
        fetch('api/get_events.php?type=closest&limit=3')
            .then(response => response.json())
            .then(events => {
                const container = document.getElementById('upcomingPreview');
                container.innerHTML = '';
                
                if (events.length === 0) {
                    container.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">No upcoming events yet.</p>';
                    return;
                }
                
                events.forEach((event, index) => {
                    const className = index === 0 ? 'imgleft' : index === 1 ? 'imgMiddle' : 'imgright';
                    const eventCard = `
                        <div class="${className}" onclick="window.location.href='event-detail.php?id=${event.id}'" style="cursor: pointer;">
                            <img src="${event.cover_image || 'assest/img-3.JPG'}" alt="${event.title}">
                            <p>${event.title}</p>
                        </div>
                    `;
                    container.innerHTML += eventCard;
                });
            });

        // Load featured event (most recent/newest created)
        fetch('api/get_events.php?type=latest&limit=1')
            .then(response => response.json())
            .then(events => {
                if (events.length > 0) {
                    const event = events[0];
                    const container = document.getElementById('featuredEvent');
                    
                    // Format date range
                    const eventDate = new Date(event.event_date);
                    const startDay = event.day;
                    const startMonth = event.month_short.toUpperCase();
                    
                    // Calculate end date (assuming 1 month duration if not specified)
                    const endDate = new Date(eventDate);
                    endDate.setMonth(endDate.getMonth() + 1);
                    const endDay = endDate.getDate();
                    const endMonth = endDate.toLocaleString('en-US', { month: 'short' }).toUpperCase();
                    
                    const dateRange = `${startDay} ${startMonth} - ${endDay} ${endMonth}`;
                    
                    container.innerHTML = `
                        <img src="${event.cover_image || 'assest/img-3.JPG'}" alt="${event.title}">
                        <div class="COVERLAKUM">
                            <div class="titleCOVERLAKUM">
                                <p>${event.title}</p>
                            </div>
                            <div class="date">
                                <p>${dateRange}</p>
                            </div>
                            <div class="paragraphCOVERLAKUM">
                                <p>${event.description || 'Discover this amazing exhibition showcasing contemporary art and culture.'}</p>
                            </div>
                        </div>
                        <div class="buttonDiscoverMoreLAKUMLEFT">
                            <button onclick="window.location.href='event-detail.php?id=${event.id}'">Discover More</button>
                        </div>
                    `;
                }
            });

        // Load most recent event for ExploreCover background
        fetch('api/get_events.php?type=latest&limit=1')
            .then(response => response.json())
            .then(events => {
                if (events.length > 0 && events[0].cover_image) {
                    const coverSection = document.getElementById('exploreCoverSection');
                    coverSection.style.backgroundImage = `url('${events[0].cover_image}')`;
                }
            })
            .catch(error => console.error('Error loading cover image:', error));

        // Load previous events (all past events from database)
        fetch('api/get_events.php?type=past')
            .then(response => response.json())
            .then(events => {
                const container = document.getElementById('previousEvents');
                container.innerHTML = '';
                
                if (events.length === 0) {
                    container.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">No previous exhibitions yet.</p>';
                    return;
                }
                
                // Split events into two columns
                const half = Math.ceil(events.length / 2);
                const column1 = events.slice(0, half);
                const column2 = events.slice(half);
                
                let col1HTML = '<div class="PhotoEventColumn">';
                column1.forEach(event => {
                    col1HTML += `
                        <div class="PhotoEventRow" onclick="window.location.href='event-detail.php?id=${event.id}'" style="cursor: pointer;">
                            <img src="${event.cover_image || 'assest/img-3.JPG'}" alt="${event.title}">
                            <p><span>${event.title}</span> <i class="ri-arrow-right-s-line"></i></p>
                        </div>
                    `;
                });
                col1HTML += '</div>';
                
                let col2HTML = '<div class="PhotoEventColumn">';
                column2.forEach(event => {
                    col2HTML += `
                        <div class="PhotoEventRow" onclick="window.location.href='event-detail.php?id=${event.id}'" style="cursor: pointer;">
                            <img src="${event.cover_image || 'assest/img-3.JPG'}" alt="${event.title}">
                            <p><span>${event.title}</span> <i class="ri-arrow-right-s-line"></i></p>
                        </div>
                    `;
                });
                col2HTML += '</div>';
                
                container.innerHTML = col1HTML + col2HTML;
            });
    </script>
</body>
</html>
