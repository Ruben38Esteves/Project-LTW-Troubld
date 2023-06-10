<?php function show_index(){ ?>
        <!DOCTYPE html>
            <html lang="en-US">
            <head>
                <link rel="stylesheet" href="html_css\index.css">
                <title>Troubld</title>
                <!-- para alterar-->  
                

            </head>
            <body>
                <header>
                    <h1><a href="../../../../../../../index.php">Troubld</a>
                        <div id="subtitle">Where your troubles are temporary</div>
                    </h1>
                    <div id="signup" style="display: block;">
                        <button type="button" class="button button1" onclick="showRegister()">
                            Register
                        </button>
                        <button type="button" class="button button1" onclick="showLogin()">
                            Login
                        </button>
                    </div>
                    <div id="login-form" style="display: none;">
                        <form method="POST" action="/php/authenticate.php"> 
                            <label>
                                <input type="text" name="email" placeholder="Email">
                            </label>
                            <br>
                            <label>
                                <input type="password" name="password" placeholder="Password">
                            </label>
                            <br>
                            <button type="submit" class="button button1">
                                Login
                            </button>
                            <br>
                            <div id="back">
                                <a href="index.html">Go Back</a>
                            </div>
                        </form>
                    </div>
                    <div id="register-form" style="display: none;">
                        <form method="POST" action= "/php/register.php">
                            <label>
                                <input type="text" name="email" placeholder="Email">
                            </label>
                            <br>
                            <label>
                                <input type="text" name="username" placeholder="Username">
                            </label>
                            <br>
                            <label>
                                <input type="text" name="name" placeholder="Name">
                            </label>
                            <br>
                            <label>
                                <input type="password" name="password" placeholder="Password">
                            </label>
                            <label>
                                <input type="password" name="password_confirm" placeholder="Confirm Password">
                            </label>
                            <label>
                                <input type="date" name="birthday">
                            </label>
                            <br>
                            <button type="submit" class="button button1">
                                Register
                            </button>
                            <br>
                            <div id="back">
                                <a href="index.html">Go Back</a>
                            </div>
                        </form>
                    </div> 
                </header>
                <div id="container">
                    <div id="image">
                        <img style="width: 1200px; height: 800px;border-radius: 5px;"  src="../loginimage.jpg" alt="Mountains">
                    </div>
                </div>
                <script>
                    function showRegister() {
                        document.getElementById("register-form").style.display = "block";
                        document.getElementById("login-form").style.display = "none";
                        document.getElementById("signup").style.display = "none";
                    }

                    function showLogin() {
                        document.getElementById("register-form").style.display = "none";
                        document.getElementById("login-form").style.display = "block";
                        document.getElementById("signup").style.display = "none";
                    }

                    function goBack(){
                        document.getElementById("register-form").style.display = "none";
                        document.getElementById("login-form").style.display = "none";
                        document.getElementById("signup").style.display = "block";
                    }
                </script>
                
            </body>'
<?php } ?>
 