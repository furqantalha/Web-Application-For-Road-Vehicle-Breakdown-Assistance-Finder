<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function getShopsData($conn) {
    $sql = "SELECT * FROM shops";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return array();
    }
}

$shopsData = getShopsData($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--========== BOX ICONS ==========-->
        <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

        <!--========== CSS ==========-->
        <link rel="stylesheet" href="assets/css/styles.css">
        <title>Vehicle breakdown</title>
        <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        #chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            border-radius: 50%;
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #chatbot-container:hover {
            background-color: #0056b3;
        }

        #chatbot-popup {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 300px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        #chatbot-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            cursor: pointer;
        }

        #chatbot-body {
            padding: 10px;
            max-height: 300px;
            overflow-y: auto;
        }

        #chatbot-input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: none;
            border-top: 1px solid #ccc;
            outline: none;
        }
    </style>
    
    </head>
    <body>
    
    
        <!--========== SCROLL TOP ==========-->
        <a href="#" class="scrolltop" id="scroll-top">
            <i class='bx bx-chevron-up scrolltop__icon'></i>
        </a>

        <!--========== HEADER ==========-->
        <header class="l-header" id="header">
            <nav class="nav bd-container">
                <a href="#" class="nav__logo">Vehicle Breakdown</a>

                <div class="nav__menu" id="nav-menu">
                    <ul class="nav__list">
                        <li class="nav__item"><a href="loginsignup.php" class="nav__link">login</a></li>
                        <li class="nav__item"><a href="#home" class="nav__link active-link">Home</a></li>
                        <li class="nav__item"><a href="#about" class="nav__link">About</a></li>
                        <li class="nav__item"><a href="#services" class="nav__link">Services</a></li>
                        <li class="nav__item"><a href="#contact" class="nav__link">Contact us</a></li>

                        <li><i class='bx bx-moon change-theme' id="theme-button"></i></li>
                    </ul>
                </div>

                <div class="nav__toggle" id="nav-toggle">
                    <i class='bx bx-menu'></i>
                </div>
            </nav>
        </header>

        <main class="l-main">
            <!--========== HOME ==========-->
            <section class="home" id="home">
                <div class="home__container bd-container bd-grid">
                    <div class="home__data">
                        <h1 class="home__title">Vehicle Breakdown</h1>
                        <h2 class="home__subtitle">No. 1  <br> Best service.</h2>
                     
                    </div>
    
                    <img src="assets/img/home (2).png" alt="" class="home__img">
                </div>
            </section>
            
            <!--========== ABOUT ==========-->
            <section class="about section bd-container" id="about">
                <div class="about__container  bd-grid">
                    <div class="about__data">
                        <span class="section-subtitle about__initial">About us</span>
                        <h2 class="section-title about__initial">vehicle <br> services</h2>
                        <p class="about__description">In 2023, Vehcile Breakdown was ideated as an accidental emergency assi after loosing a close friend to an accident. vehcile breakdown is a family of 500+ member strong team headquartered in a state of art facility at Bangalore, efficiently monitoring our pan-India service network through cutting-edge autotech solutions. Today, we proudly rank among the top-rated automotive service solution companies in India specialised in 24/7 emergency assistance and EV repairs</p>
                        <a href="#" class="button">Explore history</a>
                    </div>

                    <img src="assets/img/about (2).jpg" alt="" class="about__img">
                </div>
            </section>

            <!--========== SERVICES ==========-->
            <section class="services section bd-container" id="services">
                <span class="section-subtitle">Offering</span>
                <h2 class="section-title">Our amazing services</h2>

                <div class="services__container  bd-grid">
                    <div class="services__content">
                        <svg class="services__img" xmlns="http://www.w3.org/2000/svg">
                        </svg>
                        <h3 class="services__title">Vehicle Maintenance and Repairs</h3>
                        <p class="services__description">We excel in keeping your vehicles in top-notch condition, with skilled technicians and state-of-the-art facilities.</p>
                    </div>

                    <div class="services__content">
                        <svg class="services__img" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                            
                            <defs>
                            <clipPath id="clip0">
                            <rect width="64" height="64" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                        <h3 class="services__title">Emergency Roadside Assistance</h3>
                        <p class="services__description">Our expert team is available 24/7 to help you in case of breakdowns, accidents, or other roadside emergencies.</p>
                    </div>

                    <div class="services__content">
                        <svg class="services__img" xmlns="http://www.w3.org/2000/svg">
                           
                                <defs>
                                <clipPath id="clip0">
                                <rect width="64" height="64" fill="white"/>
                                </clipPath>
                                </defs>
                        </svg>
                        <h3 class="services__title">Customized Vehicle Upgrades</h3>
                        <p class="services__description">Enhance your vehicles with our custom upgrade options, from advanced security systems to performance enhancements.</p>
                    </div>
                </div>
            </section>

                    <div class="menu__content">
                        <style>
                           /* Map container */
                                #map {
                                height: 400px;
                                width: 100%;
                                position: relative;
                                }

                        </style>
                        <div id="map"></div>
                        <script src="https://maps.googleapis.com/maps/api/js?key=APIKEY&callback=initMap" async defer></script>
                        <script>
                        var map;
                        var marker; // Declare marker globally

                        function initMap() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function (position) {
                                    var userLocation = {
                                        lat: position.coords.latitude,
                                        lng: position.coords.longitude
                                    };

                                    map = new google.maps.Map(document.getElementById('map'), {
                                        center: userLocation,
                                        zoom: 12
                                    });

                                    const blueMarkerIcon = {
                                        path: google.maps.SymbolPath.CIRCLE,
                                        scale: 6,
                                        fillColor: "blue",
                                        fillOpacity: 1,
                                        strokeWeight: 0
                                    };

                                    marker = new google.maps.Marker({
                                        position: userLocation,
                                        map: map,
                                        title: "Your Current Location",
                                        icon: blueMarkerIcon
                                    });

                                    fetchOriginalServices();
                                }, function () {
                                    handleLocationError(true);
                                });
                            } else {
                                handleLocationError(false);
                            }
                        }

                        function fetchOriginalServices() {
                            // Fetch shop data from PHP script
                            fetch('get_data.php')
                                .then(response => response.json())
                                .then(data => {
                                    // Loop through the shop data and create markers
                                    data.forEach(shop => {
                                        const shopMarker = new google.maps.Marker({
                                            position: { lat: parseFloat(shop.lat), lng: parseFloat(shop.lng) },
                                            map: map,
                                            animation: google.maps.Animation.DROP,
                                            title: shop.shopname,
                                        });

                                        // Add click event to show info window with reviews
                                        shopMarker.addListener('click', () => {
                                            const infoWindow = new google.maps.InfoWindow({
                                                content: generateInfoWindowContent(shop),
                                            });
                                            infoWindow.open(map, shopMarker);
                                        });
                                    });
                                })
                                .catch(error => console.error('Error fetching shop data:', error));
                        }
                        function getDirectionsToService(destinationLat, destinationLng) {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function (position) {
                                var userLocation = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                };

                                const directionsService = new google.maps.DirectionsService();
                                const directionsRenderer = new google.maps.DirectionsRenderer({ map: map, polylineOptions: { strokeColor: 'blue' } });

                                const request = {
                                    origin: new google.maps.LatLng(userLocation.lat, userLocation.lng),
                                    destination: new google.maps.LatLng(destinationLat, destinationLng),
                                    travelMode: 'DRIVING',
                                };

                                directionsService.route(request, function (response, status) {
                                    if (status === 'OK') {
                                        directionsRenderer.setDirections(response);

                                        // Get distance and duration details
                                        const route = response.routes[0];
                                        const distanceInKm = route.legs[0].distance.text;
                                        const durationInHours = route.legs[0].duration.text;

                                        // Display distance and duration information in blue color
                                        const infoWindowContent = `
                                            <p style="color: blue;">Distance: ${distanceInKm}</p>
                                            <p style="color: blue;">Estimated Time: ${durationInHours}</p>
                                        `;
                                        const infoWindow = new google.maps.InfoWindow({
                                            content: infoWindowContent
                                        });

                                        // Open the info window at the destination marker's position
                                        const destinationMarker = new google.maps.Marker({
                                            position: new google.maps.LatLng(destinationLat, destinationLng),
                                            map: map,
                                            title: 'Destination'
                                        });

                                        infoWindow.open(map, destinationMarker);
                                    } else {
                                        console.error('Error fetching directions:', status);
                                        alert('Error fetching directions');
                                    }
                                });
                            }, function () {
                                handleLocationError(true);
                                alert('Error getting current location');
                            });
                        } else {
                            handleLocationError(false);
                            alert('Geolocation is not supported');
                        }
                    }



                    function generateInfoWindowContent(shop, distanceInKm, durationInMinutes, durationInHours) {
                        // Create a style element
                        const style = document.createElement('style');
                        style.innerHTML = `
                            .info-window {
                                font-family: 'Arial', sans-serif;
                                max-width: 300px;
                                padding: 10px;
                                border-radius: 5px;
                                background-color: #fff;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            }

                            .info-window strong {
                                font-size: 18px;
                                color: #333;
                            }

                            .info-window p {
                                margin: 5px 0;
                                color: #666;
                            }

                            .info-window .reviews {
                                margin-top: 10px;
                            }

                            .info-window .reviews strong {
                                display: block;
                                margin-bottom: 5px;
                                color: #333;
                            }

                            .button {
                                display: inline-block;
                                padding: 10px 15px;
                                font-size: 14px;
                                text-align: center;
                                text-decoration: none;
                                cursor: pointer;
                                border: 1px solid #3498db;
                                color: #fff;
                                background-color: #3498db;
                                border-radius: 5px;
                                transition: background-color 0.3s;
                            }

                            .button:hover {
                                background-color: #2980b9;
                            }
                        `;

                        // Append the style element to the document head
                        document.head.appendChild(style);

                        // Create the info window content
                        let content = `
                            <div class="info-window">
                                <strong>${shop.shopname}</strong><br>
                                <p>${shop.address}</p>
                                <p>${shop.description}</p>
                                <p>Average Rating: ${generateStarRating(shop.avg_rating)}</p>
                                <p>Number of Reviews: ${shop.reviews.length}</p>
                                
                        `;
                        if (shop.reviews.length > 0) {
                            content += '<div class="reviews-header" onclick="toggleReviews()">';
                            content += '<strong>Comments</strong>';
                            content += '</div>';
                            content += '<div class="reviews-content" style="display: none;">'; // Initially hidden
                            shop.reviews.forEach(review => {
                                content += `<p>${review.username} - ${review.star_rating} stars: ${review.comment}</p>`;
                            });
                            content += '</div>';
                        }

                        // Add a "Current Location" button
                        content += `<button class="button" onclick="getCurrentLocation()">Current Location</button>`;

                        // Add a button for getting directions to the service shop
                        content += `<button class="button" onclick="getDirectionsToService(${shop.lat}, ${shop.lng}, '${shop.shopname}')">Get Directions</button>`;

                        // Add a button for getting directions with estimated time
                        content += '</div>';

                        return content;
                    }

                    function toggleReviews() {
                        var reviewsContent = document.querySelector('.reviews-content');
                        if (reviewsContent.style.display === 'none') {
                            reviewsContent.style.display = 'block';
                        } else {
                            reviewsContent.style.display = 'none';
                        }
                    }
                    function showAllReviews(totalReviews) {
                        // Modify this function to display all reviews in a way that suits your design
                        // For example, you can use a modal or expand the existing reviews section
                        alert(`Displaying all ${totalReviews} reviews`);
                        // You may implement your logic to show all reviews here
                    }
                        function generateStarRating(avgRating) {
                            const roundedRating = Math.round(avgRating);
                            const stars = '★'.repeat(roundedRating) + '☆'.repeat(5 - roundedRating);
                            return `<span style="font-size: 20px; color: #f39c12;">${stars}</span>`;
                        }

                        function getCurrentLocation() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function (position) {
                                    var userLocation = {
                                        lat: position.coords.latitude,
                                        lng: position.coords.longitude
                                    };

                                    map.setCenter(userLocation);
                                    map.setZoom(12);

                                    // You can customize this part based on your requirements
                                    alert('You are now at your current location!');

                                }, function () {
                                    handleLocationError(true);
                                });
                            } else {
                                handleLocationError(false);
                            }
                        }
                        // Call the initMap function when the Google Maps API is loaded
                        google.maps.event.addDomListener(window, 'load', initMap);

                </script>
       
            </section>

            <!--===== APP =======-->
            <section class="app section bd-container">
                <div class="app__container bd-grid">
                    <div class="app__data">
                        <span class="section-subtitle app__initial">App</span>
                        <h2 class="section-title app__initial">coming soon </h2>
                        <p class="app__description">Find our application and download it will be soon, you can appoinment for emergency see your map and email on the way and much more.</p>
                        <div class="app__stores">
                            <a href="#"><img src="assets/img/app1.png" alt="" class="app__store"></a>
                            <a href="#"><img src="assets/img/app2.png" alt="" class="app__store"></a>
                        </div>
                    </div>

                    <img src="assets/img/movil-app1.png" alt="" class="app__img">
                </div>
            </section>

            <!--========== CONTACT US ==========-->
            <section class="contact section bd-container" id="contact">
                <div class="contact__container bd-grid">
                    <div class="contact__data">
                        <span class="section-subtitle contact__initial">Let's talk</span>
                        <h2 class="section-title contact__initial">Contact us</h2>
                    </div>

                    <div class="contact__button">
                    <a href="mailto:breakdownassistantfinder@gmail.com" class="button">Contact us now</a>
                </div>

                </div>
            </section>
        </main>

        <!--========== FOOTER ==========-->
        <footer class="footer section bd-container">
            <div class="footer__container bd-grid">
                <div class="footer__content">
                    <a href="#" class="footer__logo">Vehicle Breakdown</a>
                    <span class="footer__description">Service</span>
                    <div>
                        <a href="#" class="footer__social"><i class='bx bxl-facebook'></i></a>
                        <a href="#" class="footer__social"><i class='bx bxl-instagram'></i></a>
                        <a href="#" class="footer__social"><i class='bx bxl-twitter'></i></a>
                    </div>
                </div>

                <div class="footer__content">
                    <h3 class="footer__title">Services</h3>
                    <ul>
                        <li><a href="loginsignup.php" class="footer__link">emergencies</a></li>
                        <li><a href="loginsignup.php" class="footer__link">Pricing</a></li>
                        <li><a href="loginsignup.php" class="footer__link">Details</a></li>
                    </ul>
                </div>

                <div class="footer__content">
                    <h3 class="footer__title">Information</h3>
                    <ul>
                        <li><a href="#" class="footer__link">Event</a></li>
                        <li><a href="#" class="footer__link">Contact us</a></li>
                        <li><a href="#" class="footer__link">Privacy policy</a></li>
                        <li><a href="#" class="footer__link">Terms of services</a></li>
                    </ul>
                </div>

                <div class="footer__content">
                    <h3 class="footer__title">Adress</h3>
                    <ul>
                        <li>india</li>
                        <li>bangalore</li>
                        <li>999 - 888 - 777</li>
                        <li>breakdown@email.com</li>
                    </ul>
                </div>
            </div>

            <p class="footer__copy">&#169; 2023 vehicle breakdown. All right reserved</p>
        </footer>

        <!--========== SCROLL REVEAL ==========-->
        <script src="https://unpkg.com/scrollreveal"></script>

        <!--========== MAIN JS ==========-->
        <script src="assets/js/main.js"></script>
        <div id="chatbot-container" onclick="toggleChatbotPopup()">Chat</div>

<div id="chatbot-popup">
    <div id="chatbot-header" onclick="toggleChatbotPopup()">Chatbot</div>
    <div id="chatbot-body"></div>
    <input type="text" id="chatbot-input" placeholder="Type your message..." onkeyup="sendMessage(event)">
</div>

<script>
const chatbotPopup = document.getElementById('chatbot-popup');
const chatbotBody = document.getElementById('chatbot-body');
const chatbotInput = document.getElementById('chatbot-input');
let chatFlowStep = 0;

function toggleChatbotPopup() {
    chatbotPopup.style.display = chatbotPopup.style.display === 'block' ? 'none' : 'block';
}

function appendMessage(sender, message) {
    const messageDiv = document.createElement('div');
    messageDiv.innerHTML = `<strong>${sender}:</strong> ${message}`;
    chatbotBody.appendChild(messageDiv);
    chatbotBody.scrollTop = chatbotBody.scrollHeight;
}

function sendMessage(event) {
    if (event.key === 'Enter' && chatbotInput.value.trim() !== '') {
        const userMessage = chatbotInput.value.trim();
        if (userMessage.toLowerCase() === 'clear chat') {
            clearChat();
            return;
        }
        appendMessage('You', userMessage);

        if (chatFlowStep === 0) {
            // User initiated an emergency request
            appendMessage('Bot', 'Thank you for answering the emergency request. Here is the link to login: <a href="loginsignup.php">Login</a>');
            chatFlowStep = 1; // Move to the next step
        } else {
            // User is providing feedback
            appendMessage('Bot', 'Please enter your email:');
            chatFlowStep = 2; // Move to the next step
        }

        chatbotInput.value = '';

        // Save emergency request or email for feedback to the database
        saveToDatabase(userMessage);
    }
}


appendMessage('Bot', 'Hello! How can I assist you today? Please choose an option:\n1. Emergency Request\n2. Feedback');

function sendMessage(event) {
    if (event.key === 'Enter' && chatbotInput.value.trim() !== '') {
        const userMessage = chatbotInput.value.trim();
        appendMessage('You', userMessage);

        switch (chatFlowStep) {
            case 0:
                // User chooses option
                if (userMessage.toLowerCase() === '1' || userMessage.toLowerCase() === 'emergency request') {
                    appendMessage('Bot', 'Thank you for choosing an emergency request. Here is the link to login: <a href="loginsignup.php">Login</a>');
                    chatFlowStep = 1; // Move to the next step
                } else if (userMessage.toLowerCase() === '2' || userMessage.toLowerCase() === 'feedback') {
                    appendMessage('Bot', 'Great! Please enter your email:');
                    chatFlowStep = 2; // Move to the next step
                } else {
                    // Invalid choice
                    appendMessage('Bot', "I'm sorry, I didn't understand that. Please choose either '1' for Emergency Request or '2' for Feedback.");
                }
                break;
            case 1:
                // User cancels the emergency request
                if (userMessage.toLowerCase() === 'cancel') {
                    appendMessage('Bot', 'Emergency request canceled.');
                    resetChatFlow();
                } else {
                    // Continue with the feedback flow
                    appendMessage('Bot', 'Please enter your email:');
                    db.query("INSERT INTO strings (string) VALUES (?)", [email]);
                    chatFlowStep = 2; // Move to the next step
                }
                break;
            case 2:
                // User cancels providing email for feedback
                if (userMessage.toLowerCase() === 'cancel') {
                    appendMessage('Bot', 'Feedback submission canceled.');
                    resetChatFlow();
                } else {
                    // Continue with the feedback flow
                    saveFeedbackEmailToDatabase(userMessage);
                    appendMessage('Bot', 'Thank you. Please submit your feedback comment:');
                    db.query("update INTO strings  VALUES (?) where email=?", [comment],[email]);
                    chatFlowStep = 3; // Move to the next step
                }
                break;
            case 3:
                if (userMessage.toLowerCase() === 'cancel') {
                    appendMessage('Bot', 'Feedback submission canceled.');
                    resetChatFlow();
                } else {
                    // Save feedback (email and comment) to the database
                    saveFeedbackCommentToDatabase(userMessage);
                    // Display a thank you message
                    appendMessage('Bot', 'Thank you for submitting your feedback!');
                    resetChatFlow();
                }
                break;
        }
                chatbotInput.value = '';
    }
}

function resetChatFlow() {
    chatFlowStep = 0;
    saveToDatabase.email = ''; // Reset stored email
}
function clearChat() {
    chatbotBody.innerHTML = ''; // Clear chat history
}

function saveToDatabase(data) {
    if (chatFlowStep === 1) {
        // Save emergency request to the database
        saveEmergencyRequestToDatabase(data);
    } else if (chatFlowStep === 2) {
        // Save email for feedback to the database
        saveFeedbackEmailToDatabase(data);
    }
}
function saveFeedbackEmailToDatabase(email) {
    // Save email to a variable for later use
    saveToDatabase.email = email;
    // Prompt the user for feedback comment
   
    chatFlowStep = 3; // Move to the next step
}

function saveFeedbackCommentToDatabase(comment) {
    // Save feedback (email and comment) to the database
    saveToDatabase(saveToDatabase.email, comment);
}

function saveToDatabase(email, comment) {
    const data = `email=${encodeURIComponent(email)}&comment=${encodeURIComponent(comment)}`;
    sendPostRequest('chatbot.php', data, handleDatabaseResponse);
}

function handleDatabaseResponse(response) {
    // Handle the response from the database (if needed)
    console.log(response);
}

// Function to send a POST request
function sendPostRequest(url, data, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            callback(xhr.responseText);
        }
    };
    xhr.send(data);
}
</script>
    </body>
</html>