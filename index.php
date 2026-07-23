<?php 
    session_start();
    include('Details.php');
    include('DBConnection.php'); 
    
    if(isset($_SESSION['update'])){
         unset($_SESSION['update']);
    }

    // Remove the alert scripts and let header.php handle notifications
    if(isset($_SESSION["uname"])){
        $uname = $_SESSION["uname"];
        include("header2.php");
    } else {
        include("header.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>IR</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="icon/png" href="asset/img/logo/rail_icon.png">

    <link rel="stylesheet" href="asset/css/bootstrap.min.css">

    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">

    <link rel="stylesheet" href="asset/css/animate.css">

    <link rel="stylesheet" href="asset/css/hover-min.css">

    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/modern.css">

    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/validation.js"></script>

    <style>
        :root {
        --primary: #0052cc;          /* Strong blue */
        --primary-light: #2684ff;    /* Lighter blue */
        --primary-dark: #0747a6;     /* Dark blue */
        --white: #ffffff;            /* Pure white */
        --gray-50: #f5f6f7;          /* Light gray */
        --gray-100: #ecedf0;         /* Slightly darker gray */
        --gray-600: #44546f;         /* Text gray */
        --shadow: rgba(9, 30, 66, 0.25); /* Blue-tinted shadow */
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--white);
    }

    /* Hero Section */
    .hero-section {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        position: relative;
        overflow: hidden;
        padding: 4rem 0;
    }

    /* Booking Form */
    .booking-form {
        background: var(--white);
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 20px var(--shadow);
    }

    .booking-form input,
    .booking-form select {
        border: 2px solid var(--gray-100);
        padding: 0.75rem;
        border-radius: 4px;
        font-weight: 500;
    }

    .booking-form .btn {
        background: var(--primary);
        color: var(--white);
        font-weight: 600;
        padding: 0.75rem;
        border: none;
    }

    /* Train Animation */
    .train-container {
        position: absolute;
        bottom: 50px;
        width: 100%;
        height: 100px;
    }

    .train {
        position: absolute;
        bottom: 0;
        left: -200px;
        animation: trainMove 20s linear infinite;
    }

    @keyframes trainMove {
        from { transform: translateX(-200px); }
        to { transform: translateX(calc(100vw + 200px)); }
    }

    /* Features Section */
    .features-section {
        padding: 5rem 0;
        background: var(--white);     /* Changed to white */
    }

    .features-section h2 {
        color: var(--primary);        /* Black text */
        font-weight: 600;
        margin-bottom: 3rem;
    }

    .feature-card {
        background: var(--white);
        border: 1px solid var(--gray-100);
        border-radius: 8px;
        padding: 2rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px var(--shadow);
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px var(--shadow);
    }

    .feature-card i {
        color: var(--primary);
        margin-bottom: 1.5rem;
    }

    .feature-card h3 {
        color: var(--primary-dark);
        font-size: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .feature-card p {
        color: var(--gray-600);
        font-size: 1rem;
        line-height: 1.6;
    }

    #chat-container {
        position: fixed;
        bottom: 80px;  /* adjusted to leave space for icon */
        right: 20px;
        width: 320px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
        font-family: Arial, sans-serif;
        z-index: 9999;
        background-color: #fff;
        display: none; /* initially hidden */
    }

    .chat-header {
        background: var(--primary);
        color: var(--white);
    }

    .chat-box {
        height: 270px;
        background: #f8f9fa;
        overflow-y: auto;
        padding: 10px;
        font-size: 14px;
    }

    .chat-input {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        border: none;
        border-top: 1px solid #ddd;
        font-size: 14px;
    }

    /* Floating Chat Icon */
    #chat-toggle-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: var(--primary);
        border-radius: 50%;
        box-shadow: 0 4px 12px var(--shadow);
        color: var(--white);
        font-size: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10000;
        transition: background 0.3s ease;
    }
    #chat-toggle-btn:hover {
        background: var(--primary-light);
    }

    /* Scrollbar for chat-box */
    .chat-box::-webkit-scrollbar {
        width: 6px;
    }
    .chat-box::-webkit-scrollbar-thumb {
        background-color: #007bff;
        border-radius: 3px;
    }

    #bg-custom{
        background-color:rgba(2,2,2,0.8);
    }
    #m-cust{
        margin-right: 250px;
        margin-top: 60px; 
    }
    .bg-black{
        background-color:black;
    }
    .bg-img{
        background-image: url('asset/img/4.jpg');
        background-size: 100%;
        max-width: 1500px;
        min-height: 650px;
    }
    @media(max-width: 400px){
        .bg-img{
            background-image: url('asset/img/5.jpg');
            background-size: auto;
            background-repeat: no-repeat;
        }
    }
    .bg-img2{
        background-image:url('asset/img/5.jpg'); 
        background-size: 100%;
    }
    .pnr{
        background-color: white;
        color: black;
        padding-top: 10px;
        box-shadow: 2px 2px 18px 10px #222;
        border-radius: 2px;
    }
    .fs-1{
        font-size: 42px;
        font-family: Tempus Sans ITC;
        margin-top: 50px;
    }
    .fs-2{
        font-size: 18px;
        font-family: Yu Gothic Light;
        font-weight: lighter;
        margin-bottom: 50px; 
    }
    .main-name{
        font-size: 50px;
        font-family: Arial Rounded MT Bold;
        margin-top: 0px;
        background-color: rgba(2,2,2,0.2);
        border-radius: 5px;
        width:560px;
        padding-left: 50px;
    }
    .mt-10 {
        margin-top: 12rem !important;
    }

    .is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25) !important;
}

.text-danger {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.d-none {
    display: none !important;
}
    </style>

</head>
<body >
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="display-4 text-white mb-4">Welcome to Indian Railways</h1>
                    <p class="lead text-white-50">Book your journey with comfort and ease</p>
                </div>
                <div class="col-lg-6">
                    <div class="booking-form glass p-4 rounded-lg">
                        <form action="train_list.php" method="post">
                            <div class="input-group">   
                                <input type="text" name="src" class="form-control hvr-shadow" placeholder="From*" required> 
                            </div>
                            <br>
                            <div class="input-group">
                                <input type="text" name="dest" class="form-control hvr-shadow" placeholder="To*" required>
                            </div>
                            <br>
                            <div class="input-group">
                                <input type="date" 
                                       name="date" 
                                       id="journey-date"
                                       class="form-control hvr-shadow" 
                                       min="<?php echo date('Y-m-d'); ?>" 
                                       value="<?php echo date('Y-m-d'); ?>" 
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text text-dark">
                                        <img src="asset/img/logo/cal.png" width="20" height="20">
                                    </span>
                                </div>
                            </div>
                            <small id="date-error" class="text-danger d-none">Please select today or a future date</small>
                            <br>
                                <div class="form-group">
                                    <select name="class" class="form-control">
                                        <option value="General">General</option>
                                        <option value="AC">AC Class</option>
                                        <option value="Sleeper">Sleeper</option>
                                    </select>
                                </div>
                            <br>
                            <div class="input-group">
                                <input class="btn text-light bg-blue btn-block hvr-shadow" type="submit" value="Find Trains" >
                            </div><br>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="train-container">
            <img src="asset/img/train.svg" alt="Train" class="train">
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose Us</h2>
        <div class="row g-4">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-ticket-alt fa-3x text-primary mb-4"></i>
                    <h3>Easy Booking</h3>
                    <p>Book your tickets quickly and securely with just a few clicks</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-clock fa-3x text-primary mb-4"></i>
                    <h3>Real-time Updates</h3>
                    <p>Get instant updates about train timings and booking status</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-headset fa-3x text-primary mb-4"></i>
                    <h3>24/7 Support</h3>
                    <p>Our customer service team is always ready to help you</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-map-marked-alt fa-3x text-primary mb-4"></i>
                    <h3>Wide Coverage</h3>
                    <p>Access to hundreds of destinations across the country</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-4"></i>
                    <h3>Secure Payments</h3>
                    <p>Your transactions are protected with advanced security</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-user-check fa-3x text-primary mb-4"></i>
                    <h3>Easy Cancellation</h3>
                    <p>Hassle-free cancellation and quick refund process</p>
                </div>
            </div>
        </div>
    </div>
</section>

    <?php include('footer.html'); ?>

    <!-- Chatbot HTML -->
    <div id="chat-container">
      <div class="chat-header" id="chat-header">Chatbot Assistant <span style="float:right; cursor:pointer;" id="chat-close">&times;</span></div>
      <div id="chat-box" class="chat-box"></div>
      <input type="text" id="user-input" placeholder="Type your message..." class="chat-input" autocomplete="off">
    </div>

    <!-- Chat Toggle Button -->
    <div id="chat-toggle-btn" title="Chat with us">
      <i class="fas fa-comments"></i>
    </div>

    <!-- Chatbot Script -->
    <script>
      const chatContainer = document.getElementById("chat-container");
      const chatToggleBtn = document.getElementById("chat-toggle-btn");
      const chatCloseBtn = document.getElementById("chat-close");
      const chatBox = document.getElementById("chat-box");
      const userInput = document.getElementById("user-input");

      const replies = {
    "hi": "Hello! How can I assist you today?",
    "hello": "Hi there! Need help with ticket booking?",
    "what is pnr": "PNR stands for Passenger Name Record. It’s used to check booking status.",
    "check pnr": "You can check your PNR status on the 'PNR Status' page.",
    "train schedule": "Go to the 'Train Schedule' tab to see all routes and times.",
    "how to book ticket": "Visit the 'Book Ticket' section and fill in your travel details.",
    "how to cancel ticket": "Use the 'Cancel Ticket' option in the menu.",
    "ticket price": "Ticket prices depend on the class and distance. Check the schedule page.",
    "available trains": "Visit the 'Train Schedule' page to see available trains.",
    "train classes": "Train classes include Sleeper, 3A, 2A, 1A, and General.",
    "train running today": "Please check IRCTC or NTES apps for live train status.",
    "seat availability": "You can see seat availability while booking your ticket.",
    "how to get refund": "Refund is processed within 3–5 business days after ticket cancellation.",
    "train timing": "Each train has specific timings. See 'Train Schedule' for more.",
    "contact support": "You can email us at amitbellamkondi02@gmail.com or call (+91)8296567753.",
    "boarding point": "It is the station you chose while booking. Check your ticket.",
    "train number": "Train numbers are 5-digit codes used to identify specific trains.",
    "station codes": "Use Indian Railways or Google to find station codes like SBC, YPR, etc.",
    "is tatkal available": "Yes, Tatkal booking opens at 10 AM (AC) and 11 AM (non-AC) the day before travel.",
    "how to change boarding": "Currently, boarding point changes are not supported in this system.",
    "platform number": "Platform numbers are announced at the station before departure.",
    "ticket status": "Check the PNR status tab for the latest status of your ticket.",
    "train route": "The train route is available in the 'Train Schedule' section.",
    "print ticket": "You can take a screenshot or print your e-ticket directly from the booking page.",
    "how many bags allowed": "Generally, up to 40kg in sleeper class and 70kg in AC are allowed.",
    "login help": "If you're facing login issues, try resetting your password or contact support.",
    "forgot password": "Click 'Forgot Password' on the login page to reset it.",
    "how to sign up": "Click on 'Register' to create a new account.",
    "cancelled train": "Cancelled train info is available on IRCTC or by checking your PNR.",
    "delayed train": "Use NTES app or IRCTC to track delayed trains live.",
    "how to change ticket": "Modifying booked tickets isn't supported. Please cancel and rebook.",
    "is catering available": "Catering is available on select trains. Check IRCTC for details.",
    "mobile ticket valid": "Yes, mobile tickets are valid. Just show the SMS or PDF.",
    "ticket expired": "Tickets are valid only for the journey date. Please book again.",
    "payment options": "We accept UPI, credit/debit cards, and net banking.",
    "transaction failed": "If payment failed but money deducted, wait 24 hrs or contact support.",
    "how to reschedule": "Rescheduling is not allowed. Please cancel and rebook.",
    "concession fare": "Student and senior citizen concessions may be available on IRCTC bookings.",
    "group booking": "Group bookings must be done via IRCTC or railway counters.",
    "is id proof needed": "Yes, ID proof is mandatory during travel.",
    "journey rules": "Carry ID proof and reach the station 30 minutes before departure.",
    "luggage policy": "Maximum luggage depends on your class. AC classes have more allowance.",
    "train cancellation refund": "Full refund is given if train is cancelled by Indian Railways.",
    "can i travel with wl ticket": "You can’t board with a fully waitlisted e-ticket.",
    "gnwl means": "GNWL is General Wait List — it’s common for most bookings.",
    "tatkal charges": "Tatkal charges are extra and depend on class and distance.",
    "how to check route map": "Train route map is available in 'Train Schedule' under each train.",
    "ac vs sleeper": "AC has more comfort and costs more. Sleeper is economical.",
    "toilet facilities": "Indian Railways provides both Indian and Western-style toilets in coaches.",
    "is offline booking available": "This system supports only online bookings currently.",
    "site not loading": "Try refreshing the page or check your internet connection.",
    "ticket not confirmed": "Keep checking PNR. If it stays WL, it may not be confirmed.",
    "thank you": "You're welcome! 😊",
    "bye": "Goodbye! Have a great day!",
    "ok": "Alright!",
    "help": "I'm here to help. Try asking about booking, canceling, or train schedules.",
    "what is ai": "AI stands for Artificial Intelligence. It enables machines to mimic human intelligence.",
    "how are you": "I'm just a bot, but I'm doing great! 😊",
    "train schedule": "To view train schedules, click on the 'Train Schedule' tab above.",
    "pnr status": "You can check your PNR status by clicking on 'PNR Status' in the menu.",
    "cancel ticket": "To cancel a ticket, go to the 'Cancel Ticket' section and enter your booking details.",
    "book ticket": "You can book tickets from the 'Book Ticket' page. Make sure you're logged in!",
    "how to contact": "You can reach us via email: raghu@gmail.com or phone: (+91) 12345678.",
    "location": "We are located at KLE JT BCA College Gadag, Hatalageri Naka, Gadag, Karnataka.",
    "developers": "This system was developed by Raghavendra V M and Amith S B.",
    "forgot password": "Click on 'Forgot Password' on the login page and follow the steps.",
    "AI": "AI stands for Artificial Intelligence. It enables machines to mimic human intelligence.",
    "How are you?": "I'm just a bot, but I'm doing great! 😊",
      };

      // Toggle chatbot visibility
      chatToggleBtn.addEventListener("click", () => {
          if(chatContainer.style.display === "none" || chatContainer.style.display === ""){
              chatContainer.style.display = "block";
              userInput.focus();
          } else {
              chatContainer.style.display = "none";
          }
      });

      // Close chatbot with close button
      chatCloseBtn.addEventListener("click", () => {
          chatContainer.style.display = "none";
      });

      // Chatbot interaction on enter key
      userInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
          const inputRaw = userInput.value.trim();
          const input = inputRaw.toLowerCase();
          if (input === "") return;

          chatBox.innerHTML += `<div><strong>You:</strong> ${inputRaw}</div>`;

          let response = replies[input];
          if(!response){
            response = replies["default"];
          }

          chatBox.innerHTML += `<div><strong>Bot:</strong> ${response}</div>`;

          userInput.value = "";
          chatBox.scrollTop = chatBox.scrollHeight;
        }
      });
    </script>

    <!-- Add this before </body> but after other scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('journey-date');
        const dateError = document.getElementById('date-error');
        const today = new Date().toISOString().split('T')[0];

        // Set minimum date
        dateInput.min = today;

        // Date validation function
        function validateDate() {
            if (dateInput.value < today) {
                dateInput.classList.add('is-invalid');
                dateError.classList.remove('d-none');
                dateInput.value = today;
                return false;
            }
            dateInput.classList.remove('is-invalid');
            dateError.classList.add('d-none');
            return true;
        }

        // Validate on input
        dateInput.addEventListener('input', validateDate);

        // Validate on form submit
        dateInput.closest('form').addEventListener('submit', function(e) {
            if (!validateDate()) {
                e.preventDefault();
            }
        });
    });
    </script>

</body>
</html>
