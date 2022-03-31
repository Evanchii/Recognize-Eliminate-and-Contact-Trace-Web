<html>

<head>
    <title>About Us - REaCT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Asap:wght@400;500&family=Quicksand:wght@400;500&display=swap');

    *,
    *:before,
    *:after {
        font-family: 'Asap', sans-serif;
        font-family: 'Quicksand', sans-serif;
        text-decoration: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    body {
        background-image: radial-gradient(#b5b5b5 10%, transparent 0%);
        background-color: #e0e0e0;
        background-position: 0 0, 50px 50px;
        background-size: 20px 20px;
        line-height: 200%;
    }

    header {
        color: white;
        display: flex;
        justify-content: space-between;
        background: #0C112D;
        padding: 0.5% 2%;
        align-items: center;
    }

    header img {
        width: 15vw;
        filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(91deg) brightness(104%) contrast(104%);
    }

    .content {
        background: white;
        width: 60vw;
        margin: 5% auto;
        padding: 2% 3%;
        border-radius: 15px;
        box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.75);
        -webkit-box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.75);
    }
	
	.content img{
		width: 10vw;
		margin-left: auto;
		margin-right: auto;
		display: block;
	}
	
	.content .Footer{
		font-size: 12px;
		text-align: center;
		margin-top: 40px;
	}
    </style>
</head>

<body>
<header onclick="window.location = '../'">
        <img src="../assets/text-logo.png" alt="REaCT - CORE">
        <h2>About Us</h2>
    </header>
    <div class="content">
	
	
	<img src="../assets/logo.png" alt="REaCT - CORE">
	<img src="../assets/text-logo.png" alt="REaCT - CORE">
    <h1></h1><br>

    <h2>What is REaCT?</h2>

	<ul>
        <li>REaCT is a contact tracing application which stands for Recognize, Eliminate, and Contact Trace. </li>
        <li>This app helps you to remember where establishment you have entered and notifies you quickly if you have 
		been in exposed with someone who has tested positive to COVID-19 virus.</li>
        <li>REaCT is using facial detection and recognition when entering the establishment to make contact tracing more 
		easier and making it quicker to stop the spread of the virus.</li>
	</ul>
	
	<br>
	<h3>Our Mission</h3>

    <p>REaCT helps to alleviate the problems encountering in the existing contact tracing system. This app will improve the 
	traditional way of contact tracing in malls and other establishments to speed up the current manual processes and making 
	it quicker to stop the spread of the virus by using an automatic process based on face detection and recognition.</p>

	<div class="Footer">

      © 2021 REaCT. All right reserved

    </div>
    
</div>
</body>

</html>