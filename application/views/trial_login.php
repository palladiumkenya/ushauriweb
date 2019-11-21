<!DOCTYPE html>
<html>
    <style>
        @import url(line-icons.css);	
        @import url(http://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic);
        @import "https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css";
        /* Full-width input fields */
        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 15px;
        }




        input:focus {
            outline: none;
            border: 1px solid #007aff;
            color: c0392b;
        }

        input:focus + .fa {
            color: #007aff;
        }

        .input-icons {
            position: relative;
            margin: 5px 0px 5px 0px;
        }

        .input-icons > input {
            text-indent: 17px;
            font-family: "Lato", sans-serif;
        }

        .input-icons > .fa-user {
            position: absolute;
            top: 21px;
            left: 7px;
            font-size: 17px;
            color: #777777;
        }

        .input-icons > .fa-lock {
            position: absolute;
            top: 21px;
            left: 7px;
            font-size: 17px;
            color: #777777;
        }


        a {
            color: blue;
        }	



        /* Set a style for all buttons */
        button {
            background-color: #007aff;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: 1px solid #007aff;
            cursor: pointer;
            width: 100%;
            text-transform: uppercase;
            font-weight: 300;
            font-family: 'Lato', sans-serif;
            margin-bottom: 20px;
        }

        button:hover {
            opacity: 0.8;
            background-color: #d5d7de;
            border: thin;
            border: 1px solid #007aff;
            color: #007aff;
        }


        /* Center the image and position the close button */
        .imgcontainer {
            text-align: center;
            margin: 10px 0 12px 0;
            position: relative;
        }
        .imgcontainer1 {
            text-align: center;
            margin: 45px 20px 0 0;
            position: relative;
            width: 90%;
            height: 30%;
        }

        img.avatar {
            width: 40%;
            border-radius: 50%;
        }

        .container {
            padding: 16px;
        }

        span.psw {
            float: right;
            padding-top: 16px;
            margin-right: 8px;
        }
        .wel {
            margin-top: 20px;
            font-size: 25px;
            width: 100%;
            font-weight: 500px;
            color: #007aff;
            font-family: 'Lato', sans-serif;
            margin-bottom: 20px;
        }
        .logo{


        }
        /* Modal Content/Box */
        .modal-content {
            background-color: #d5d7de;
            margin: 2% auto 15% auto; /* 2% from the top, 15% from the bottom and centered */
            border: none;
            width: 26%; /* Could be more or less, depending on screen size */
        }

        /* Add Zoom Animation */
        .animate {
            -webkit-animation: animatezoom 0.6s;
            animation: animatezoom 0.6s
        }

        @-webkit-keyframes animatezoom {
            from {-webkit-transform: scale(0)} 
            to {-webkit-transform: scale(1)}
        }

        @keyframes animatezoom {
            from {transform: scale(0)} 
            to {transform: scale(1)}
        }

        /* Change styles for span and cancel button on extra small screens */
        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }

        </style>
        <body>

            <div id="id01" class="modal">
                <div class="imgcontainer1">
                    <img src="T4A3 logo.png" width="21%" height="82">
                </div>
                <form class="modal-content animate" action="/action_page.php">

                    <div class="container">

                        <div class="imgcontainer">
                            <label class="wel">Welcome</label>
                        </div>
                        <div class="input-icons">
                            <input type="text" placeholder="Username" name="uname" required>
                            <span class="fa fa-user"></span>
                        </div>
                        <div class="input-icons">
                            <input type="password" placeholder="Password" name="psw" required>
                            <span class="fa fa-lock"></span>
                        </div>
                        <input type="checkbox" checked="checked"> Remember Password
                        <button type="submit">Login</button>  
                        <br>
                        <label class="psw"> <a href="#">Forgot Password?</a></label>
                    </div>

                </form>
            </div>

            <script>
                // Get the modal
                var modal = document.getElementById('id01');

            </script>

        </body>
    </html>
