<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAKUM - About Us</title>
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

    <div class="pastExhibit" id="recentEvents">
        <!-- 3 most recent/closest events will be loaded here -->
    </div>

    <section class="bannerAhmedMatter" id="closestEvent">
        <!-- Closest event will be loaded here -->
    </section>

    <div class="driveyoursoul">
        <div class="titledriveyoursoul">
            <h4>Driven by Soul, Made by hands</h4>
        </div>
        <div class="higlightdriveyoursoul">
            <p>Explore our previous exhibitions</p>
        </div>
        <div class="buttonDiscoverMoreDriveYourSoul">
            <button><a href="Space.html#form">DISCOVER MORE</a></button>
        </div>
    </div>

    <div class="upComingExhibitions">
        <hr class="line">
        <div class="titleExhibitions">
            <h4>UPCOMING EXHIBITIONS</h4>
        </div>
    </div>

    <div class="bannerDrivenYourSoul" id="upcomingBanner">
        <!-- Closest upcoming event will be loaded here -->
    </div>

    <div class="upcomingContianerAboutUs" id="nextTwoEvents">
        <!-- Next 2 upcoming events will be loaded here -->
    </div>

    <div class="ContainerYourown">
        <div class="titleYourown">
            <h4>CREATE YOUR OWN EVENT</h4>
        </div>
        <div class="buttonDiscoverMoreYourown">
            <button>DISCOVER MORE</button>
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
        // Load most recent past events (up to 3, but only as many as exist)
        console.log('AboutUs.php: Loading pastExhibit section...');
        fetch('api/get_events.php?type=past&limit=3')
            .then(response => response.json())
            .then(events => {
                console.log('pastExhibit: Loaded', events.length, 'past events', events);
                const container = document.getElementById('recentEvents');
                container.innerHTML = '';
                
                if (events.length === 0) {
                    container.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">No past events yet.</p>';
                    return;
                }
                
                events.forEach((event, index) => {
                    const className = index === 0 ? 'imgleft' : index === 1 ? 'imgMiddle' : 'imgright';
                    container.innerHTML += `
                        <div class="${className}" onclick="window.location.href='event-detail.php?id=${event.id}'" style="cursor: pointer;">
                            <img src="${event.cover_image || 'assest/img-3.JPG'}" alt="${event.title}">
                            <p>${event.title}</p>
                        </div>
                    `;
                });
                console.log('pastExhibit: Rendered successfully');
            })
            .catch(error => console.error('pastExhibit: Error loading events', error));

        // Load closest event for banner
        console.log('AboutUs.php: Loading bannerAhmedMatter section...');
        fetch('api/get_events.php?type=closest&limit=1')
            .then(response => response.json())
            .then(events => {
                console.log('bannerAhmedMatter: Loaded', events.length, 'event(s)', events);
                if (events.length > 0) {
                    const event = events[0];
                    const container = document.getElementById('closestEvent');
                    
                    // Format date range
                    const dateRange = `${event.day} ${event.month_short.toUpperCase()} - ${event.day} ${event.month_short.toUpperCase()}`;
                    
                    container.innerHTML = `
                        <div class="bannerleftside">
                            <div class="date"><p>${dateRange}</p></div>
                            <div class="name"><h2>${event.title}</h2></div>
                            <div class="year"><p>${event.year}</p></div>
                        </div>
                        <div class="bannerrightside" style="background-image: url('${event.cover_image || 'assest/img-3.JPG'}');"></div>
                    `;
                    container.style.cursor = 'pointer';
                    container.onclick = () => window.location.href = `event-detail.php?id=${event.id}`;
                    console.log('bannerAhmedMatter: Rendered event -', event.title);
                } else {
                    document.getElementById('closestEvent').innerHTML = '<p style="text-align: center; padding: 40px;">No upcoming events yet.</p>';
                    console.log('bannerAhmedMatter: No events found');
                }
            })
            .catch(error => console.error('bannerAhmedMatter: Error loading event', error));

        // Load upcoming banner (closest upcoming event)
        console.log('AboutUs.php: Loading bannerDrivenYourSoul section...');
        fetch('api/get_events.php?type=closest&limit=1')
            .then(response => response.json())
            .then(events => {
                console.log('bannerDrivenYourSoul: Loaded', events.length, 'event(s)', events);
                if (events.length > 0) {
                    const event = events[0];
                    const container = document.getElementById('upcomingBanner');
                    
                    // Format date
                    const dateStr = `${event.day} ${event.month_short.toUpperCase()}`;
                    
                    container.innerHTML = `
                        <div class="bannerleftsideDrivenYourSoul" style="background-image: url('${event.cover_image || 'assest/img-3.JPG'}');"></div>
                        <div class="bannerrightsideDrivenYourSoul">
                            <div class="datebanner"><p>${dateStr}</p></div>
                            <div class="namebanner"><h2>${event.title}</h2></div>
                            <div class="yearbanner"><p>${event.year}</p></div>
                            <div class="paragraphbanner"><p>${event.description || 'Discover this amazing exhibition showcasing contemporary art and culture.'}</p></div>
                            <div class="buttonDiscoverMoreDriveYourSoulCoverBanner">
                                <button onclick="window.location.href='event-detail.php?id=${event.id}'"><a href="event-detail.php?id=${event.id}">DISCOVER MORE</a></button>
                            </div>
                        </div>
                    `;
                    console.log('bannerDrivenYourSoul: Rendered event -', event.title, 'with description:', event.description);
                } else {
                    document.getElementById('upcomingBanner').innerHTML = '<p style="text-align: center; padding: 40px;">No upcoming events yet.</p>';
                    console.log('bannerDrivenYourSoul: No events found');
                }
            })
            .catch(error => console.error('bannerDrivenYourSoul: Error loading event', error));

        // Load next 2 upcoming events (or as many as exist)
        console.log('AboutUs.php: Loading upcomingContianerAboutUs section...');
        fetch('api/get_events.php?type=closest&limit=3')
            .then(response => response.json())
            .then(events => {
                console.log('upcomingContianerAboutUs: Loaded', events.length, 'total events', events);
                const nextTwo = events.slice(1, 3); // Skip first (already shown in banner), get next 2
                console.log('upcomingContianerAboutUs: Showing next 2 events:', nextTwo);
                const container = document.getElementById('nextTwoEvents');
                container.innerHTML = '';
                
                if (nextTwo.length === 0) {
                    container.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">No more upcoming events.</p>';
                    console.log('upcomingContianerAboutUs: No additional events to show');
                    return;
                }
                
                nextTwo.forEach(event => {
                    container.innerHTML += `
                        <div class="upcomingEventAboutUsPage" onclick="window.location.href='event-detail.php?id=${event.id}'" style="cursor: pointer;">
                            <div class="ComingEventContainerAboutUsPage">
                                <div class="imgHeaderAboutUsPage">
                                    <img src="${event.cover_image || 'assest/img-3.JPG'}" alt="${event.title}">
                                    <div class="COVERAboutUsPageLukum">
                                        <div class="leftColumnPage">
                                            <div class="dateAboutUs">
                                                <p>${event.month_short.toUpperCase()}</p>
                                                <p class="pnumber">${event.day}</p>
                                            </div>
                                        </div>
                                        <div class="rightColumnPage">
                                            <div class="titleCoverAboutUsLakum"><p>${event.title}</p></div>
                                            <div class="timeAboutUsLAKUM"><p>${event.event_time || '17:00 - 22:00'}</p></div>
                                            <div class="dateCoverAboutUsLAKUM"><p>${event.day}${event.month_short.toUpperCase()}</p></div>
                                            <div class="buttonDiscoverMoreDriveYourSoulComing">
                                                <button><a href="event-detail.php?id=${event.id}">DISCOVER MORE</a></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                console.log('upcomingContianerAboutUs: Rendered', nextTwo.length, 'events successfully');
            })
            .catch(error => console.error('upcomingContianerAboutUs: Error loading events', error));
    </script>
</body>
</html>
