<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Events Calendar - LAKUM</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="Home.css">
    <link rel="stylesheet" href="Calender.css">
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

    <div class="calendarContainer" style="display: flex !important; visibility: visible !important; opacity: 1 !important;">
        <div class="monthsSidebar" style="display: block !important; visibility: visible !important;">
            <ul id="monthsList">
                <!-- Months will be loaded dynamically -->
            </ul>
        </div>

        <div class="eventsList" id="eventsList" style="display: block !important; visibility: visible !important; opacity: 1 !important; flex: 1 !important;">
            <!-- Events will be loaded dynamically -->
        </div>
    </div>

    <div class="upcomingEventCalender">
        <div class="ComingEventContainerCalender" id="closestTwoEvents">
            <!-- Closest 2 upcoming events will be loaded here -->
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
        let allEvents = [];
        let availableMonths = [];

        // Load all upcoming events
        fetch('api/get_events.php?type=upcoming')
            .then(response => response.json())
            .then(events => {
                allEvents = events;
                
                if (!events || events.length === 0) {
                    document.getElementById('monthsList').innerHTML = '<li style="background: #f5f5f5; cursor: default;">No upcoming events</li>';
                    document.getElementById('eventsList').innerHTML = '<p style="text-align: center; padding: 40px; color: #666; font-size: 18px;">No upcoming events yet. Check back soon!</p>';
                    return;
                }
                
                // Get unique months
                const monthsSet = new Set();
                events.forEach(event => {
                    if (event.month) {
                        monthsSet.add(event.month);
                    }
                });
                availableMonths = Array.from(monthsSet);
                
                // Populate months sidebar
                const monthsList = document.getElementById('monthsList');
                monthsList.innerHTML = '';
                availableMonths.forEach((month, index) => {
                    const li = document.createElement('li');
                    li.textContent = month;
                    li.dataset.month = month.toLowerCase();
                    if (index === 0) li.classList.add('active');
                    li.addEventListener('click', () => {
                        filterByMonth(month, li);
                    });
                    monthsList.appendChild(li);
                });
                
                // Show first month by default
                if (availableMonths.length > 0) {
                    filterByMonth(availableMonths[0]);
                }
            })
            .catch(error => {
                console.error('Error loading events:', error);
                document.getElementById('eventsList').innerHTML = '<p style="text-align: center; padding: 40px; color: #c33;">Error loading events. Please refresh the page.</p>';
            });

        function filterByMonth(month, clickedElement) {
            // Update active state
            document.querySelectorAll('.monthsSidebar li').forEach(li => li.classList.remove('active'));
            if (clickedElement) {
                clickedElement.classList.add('active');
            } else {
                const firstMonth = document.querySelector('.monthsSidebar li');
                if (firstMonth) firstMonth.classList.add('active');
            }
            
            // Filter events
            const filteredEvents = allEvents.filter(event => {
                return event.month && event.month.toLowerCase() === month.toLowerCase();
            });
            
            // Display events
            const container = document.getElementById('eventsList');
            if (!container) return;
            
            container.innerHTML = '';
            
            if (filteredEvents.length === 0) {
                container.innerHTML = '<p style="text-align: center; padding: 40px; color: #666; font-size: 18px;">No events for this month</p>';
                return;
            }
            
            // Create events HTML
            let eventsHTML = '';
            for (let i = 0; i < filteredEvents.length; i += 2) {
                eventsHTML += '<div class="firstRow" style="display: grid !important; grid-template-columns: repeat(2, 1fr) !important; gap: 30px !important; margin-bottom: 30px !important; visibility: visible !important; opacity: 1 !important;">';
                
                // First event in row
                const event1 = filteredEvents[i];
                eventsHTML += createEventHTML(event1);
                
                // Second event in row (if exists)
                if (i + 1 < filteredEvents.length) {
                    const event2 = filteredEvents[i + 1];
                    eventsHTML += createEventHTML(event2);
                }
                
                eventsHTML += '</div>';
            }
            
            container.innerHTML = eventsHTML;
        }

        function createEventHTML(event) {
            const coverImage = event.cover_image || 'assest/img-3.JPG';
            const monthShort = event.month_short ? event.month_short.toUpperCase() : 'JAN';
            const day = event.day || '01';
            
            return `
                <div class="eventItem" onclick="window.location.href='event-detail.php?id=${event.id}'" style="cursor: pointer; display: block !important; visibility: visible !important; opacity: 1 !important;">
                    <div class="imgWrapperCalender" style="display: block !important; visibility: visible !important;">
                        <img src="${coverImage}" alt="${event.title}" style="display: block !important; visibility: visible !important;">
                        <div class="dateBoxCalender" style="display: block !important; visibility: visible !important;">
                            <p>${monthShort}</p>
                            <p>${day}</p>
                        </div>
                    </div>
                    <div class="titleBox" style="display: block !important; visibility: visible !important;">${event.title}</div>
                </div>
            `;
        }

        // Load closest 2 upcoming events (or as many as exist)
        fetch('api/get_events.php?type=closest&limit=2')
            .then(response => response.json())
            .then(events => {
                const container = document.getElementById('closestTwoEvents');
                container.innerHTML = '';
                
                if (events.length === 0) {
                    container.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">No upcoming events yet.</p>';
                    return;
                }
                
                events.forEach(event => {
                    container.innerHTML += `
                        <div class="upcomingEventAboutUs" onclick="window.location.href='event-detail.php?id=${event.id}'" style="cursor: pointer;">
                            <div class="ComingEventContainerAboutUs">
                                <div class="imgHeaderAboutUs">
                                    <img src="${event.cover_image || 'assest/img-3.JPG'}" alt="${event.title}">
                                    <div class="COVERAboutUs">
                                        <div class="leftColumn">
                                            <div class="dateAboutUs">
                                                <p>${event.month_short.toUpperCase()}</p>
                                                <p class="pnumber">${event.day}</p>
                                            </div>
                                        </div>
                                        <div class="rightColumn">
                                            <div class="titleCoverAboutUs"><p>${event.title}</p></div>
                                            <div class="timeAboutUs"><p>${event.event_time || '17:00 - 22:00'}</p></div>
                                            <div class="dateCoverAboutUs"><p>${event.day}${event.month_short.toUpperCase()}</p></div>
                                            <div class="buttonDiscoverMoreDriveYourSoulComing">
                                                <button>DISCOVER MORE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });
    </script>
</body>
</html>
